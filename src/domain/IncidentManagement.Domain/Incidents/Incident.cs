using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Comments;
using IncidentManagement.Domain.Incidents.Errors;
using IncidentManagement.Domain.Incidents.ValueObjects;
using IncidentManagement.Domain.IncidentParticipants;
using IncidentManagement.Domain.IncidentParticipants.ValueObjects;
using IncidentManagement.Domain.PostmortemReports;
using IncidentManagement.Domain.Subscriptions;
using IncidentManagement.Domain.TimelineEvents;
using IncidentManagement.Domain.TimelineEvents.ValueObjects;
using IncidentManagement.Domain.Users.ValueObjects;

namespace IncidentManagement.Domain.Incidents;

public sealed class Incident : AggregateRoot<IncidentId>
{
    private Comment[] _comments = [];
    private IncidentParticipant[] _participants = [];
    private Subscription[] _subscriptions = [];
    private TimelineEvent[] _timelineEvents = [];

    private Incident()
    {
    }

    public string Title { get; private set; } = string.Empty;

    public string Description { get; private set; } = string.Empty;

    public IncidentStatus Status { get; private set; }

    public Severity Severity { get; private set; }

    public DateTimeOffset DeclaredAt { get; private set; }

    public DateTimeOffset? ResolvedAt { get; private set; }

    public PostmortemReport? PostmortemReport { get; private set; }

    public IReadOnlyCollection<IncidentParticipant> Participants => _participants;

    public IReadOnlyCollection<TimelineEvent> TimelineEvents => _timelineEvents;

    public IReadOnlyCollection<Comment> Comments => _comments;

    public IReadOnlyCollection<Subscription> Subscriptions => _subscriptions;

    public static Result<Incident> Create(
        IncidentId id,
        string title,
        string description,
        Severity severity,
        DateTimeOffset declaredAt,
        TimelineEventId timelineEventId)
    {
        if (string.IsNullOrWhiteSpace(title))
        {
            return Result<Incident>.Failure<Incident>(IncidentErrors.TitleRequired);
        }

        if (string.IsNullOrWhiteSpace(description))
        {
            return Result<Incident>.Failure<Incident>(IncidentErrors.DescriptionRequired);
        }

        var incident = new Incident
        {
            Id = id,
            Title = title.Trim(),
            Description = description.Trim(),
            Severity = severity,
            DeclaredAt = declaredAt,
            Status = IncidentStatus.Open
        };

        incident.AppendTimelineEvent(timelineEventId, TimelineEventType.SystemEvent, "Incident created.", declaredAt);

        return Result<Incident>.Success(incident);
    }

    public Result AssignOwner(
        IncidentParticipantId participantId,
        UserId ownerId,
        UserId assignedByUserId,
        DateTimeOffset assignedAt,
        TimelineEventId timelineEventId)
    {
        RemoveOwnerParticipants();

        Result<IncidentParticipant> participant = IncidentParticipant.Create(
            participantId,
            ownerId,
            ParticipantRole.Owner,
            assignedByUserId,
            assignedAt);

        _participants = Append(_participants, participant.Value!);

        AppendTimelineEvent(timelineEventId, TimelineEventType.OwnerAssigned, $"Owner assigned to {ownerId}.", assignedAt);

        return Result.Success();
    }

    public Result AddResponder(
        IncidentParticipantId participantId,
        UserId responderId,
        UserId assignedByUserId,
        DateTimeOffset assignedAt)
    {
        Result<IncidentParticipant> participant = IncidentParticipant.Create(
            participantId,
            responderId,
            ParticipantRole.Responder,
            assignedByUserId,
            assignedAt);

        _participants = Append(_participants, participant.Value!);

        return Result.Success();
    }

    public Result ChangeStatus(IncidentStatus status, DateTimeOffset occurredAt, TimelineEventId timelineEventId)
    {
        if (Status == status)
        {
            return Result.Success();
        }

        Result canEnterStatusResult = CanEnter(status);
        if (canEnterStatusResult.IsFailure)
        {
            return canEnterStatusResult;
        }

        ApplyStatus(status, occurredAt);
        AppendTimelineEvent(timelineEventId, TimelineEventType.StatusChanged, $"Status changed to {status}.", occurredAt);

        return Result.Success();
    }

    public void ChangeSeverity(Severity severity, DateTimeOffset occurredAt, TimelineEventId timelineEventId)
    {
        if (Severity == severity)
        {
            return;
        }

        Severity = severity;
        AppendTimelineEvent(timelineEventId, TimelineEventType.SeverityChanged, $"Severity changed to {severity}.", occurredAt);
    }

    public void AddComment(Comment comment, TimelineEventId timelineEventId)
    {
        _comments = Append(_comments, comment);

        AppendTimelineEvent(timelineEventId, TimelineEventType.CommentPosted, "Comment posted.", comment.WrittenAt);
    }

    public void Subscribe(Subscription subscription)
    {
        _subscriptions = Append(_subscriptions, subscription);
    }

    public Result CreatePostmortem(PostmortemReport postmortemReport)
    {
        if (Status is not IncidentStatus.Resolved and not IncidentStatus.Closed)
        {
            return Result.Failure(IncidentErrors.PostmortemRequiresResolvedOrClosedIncident);
        }

        if (PostmortemReport is not null)
        {
            return Result.Failure(IncidentErrors.PostmortemAlreadyExists);
        }

        PostmortemReport = postmortemReport;

        return Result.Success();
    }

    private Result CanEnter(IncidentStatus status)
    {
        // checking only for owner, as we don't have Team entity set in the task and therefore, we're not checking for team membership
        if (status == IncidentStatus.Investigating && !HasOwner())
        {
            return Result.Failure(IncidentErrors.OwnerRequiredForInvestigation);
        }

        return Result.Success();
    }

    private void ApplyStatus(IncidentStatus status, DateTimeOffset occurredAt)
    {
        Status = status;

        if (status == IncidentStatus.Resolved)
        {
            ResolvedAt = occurredAt;
            return;
        }

        if (ResolvedAt is not null && status is IncidentStatus.Open or IncidentStatus.Investigating or IncidentStatus.Mitigating)
        {
            ResolvedAt = null;
        }
    }

    private bool HasOwner() => _participants.Any(participant => participant.Role == ParticipantRole.Owner);

    private void AppendTimelineEvent(TimelineEventId id, TimelineEventType type, string message, DateTimeOffset occurredAt)
    {
        Result<TimelineEvent> timelineEvent = TimelineEvent.Create(id, type, message, occurredAt, raisedBySystem: true);

        if (timelineEvent.IsSuccess)
        {
            _timelineEvents = Append(_timelineEvents, timelineEvent.Value!);
        }
    }

    private void RemoveOwnerParticipants()
    {
        int ownerCount = _participants.Count(participant => participant.Role == ParticipantRole.Owner);
        if (ownerCount == 0)
        {
            return;
        }

        var participants = new IncidentParticipant[_participants.Length - ownerCount];
        var index = 0;

        foreach (IncidentParticipant participant in _participants)
        {
            if (participant.Role != ParticipantRole.Owner)
            {
                participants[index] = participant;
                index++;
            }
        }

        _participants = participants;
    }

    private static T[] Append<T>(T[] source, T item)
    {
        var destination = new T[source.Length + 1];
        Array.Copy(source, destination, source.Length);
        destination[^1] = item;

        return destination;
    }
}
