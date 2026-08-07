using System.Linq;
using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;
using IncidentManagement.Domain.ValueObjects;

namespace IncidentManagement.Domain.Entities;

public sealed class Incident : Entity
{
    private readonly List<IncidentParticipant> _participants = new();
    private readonly List<TimelineEvent> _timelineEvents = new();
    private readonly List<Comment> _comments = new();
    private readonly List<Subscription> _subscriptions = new();
    private readonly List<ActionItem> _actionItems = new();

    public string Title { get; private set; }
    public string Description { get; private set; }
    public IncidentStatus Status { get; private set; }
    public Severity Severity { get; private set; }
    public DateTimeOffset DeclaredAt { get; }
    public DateTimeOffset? ResolvedAt { get; private set; }
    public Guid? TeamId { get; private set; }
    public PostmortemReport? Postmortem { get; private set; }

    public IReadOnlyCollection<IncidentParticipant> Participants => _participants.AsReadOnly();
    public IReadOnlyCollection<TimelineEvent> TimelineEvents => _timelineEvents.AsReadOnly();
    public IReadOnlyCollection<Comment> Comments => _comments.AsReadOnly();
    public IReadOnlyCollection<Subscription> Subscriptions => _subscriptions.AsReadOnly();
    public IReadOnlyCollection<ActionItem> ActionItems => _actionItems.AsReadOnly();

    public IncidentParticipant? Owner => _participants.FirstOrDefault(p => p.Role == ParticipantRole.Owner);
    public bool HasOwner => Owner is not null;

    public Incident(
        Guid id,
        string title,
        string description,
        Severity severity,
        DateTimeOffset declaredAt,
        Guid? teamId = null) : base(id)
    {
        if (string.IsNullOrWhiteSpace(title))
            throw new ArgumentException("Title cannot be empty.", nameof(title));

        Title = title;
        Description = description ?? string.Empty;
        Severity = severity;
        DeclaredAt = declaredAt;
        TeamId = teamId;
        Status = IncidentStatus.Open;

        AppendTimelineEvent(TimelineEventType.StatusChanged, $"Incident declared with status {Status}.", declaredAt, raisedBySystem: true);
    }

    public void AssignTeam(Guid teamId)
    {
        TeamId = teamId;
    }

    public void ChangeSeverity(Severity newSeverity, DateTimeOffset occurredAt, bool raisedBySystem = false)
    {
        if (newSeverity == Severity)
            return;

        var previous = Severity;
        Severity = newSeverity;
        AppendTimelineEvent(TimelineEventType.SeverityChanged, $"Severity changed from {previous} to {newSeverity}.", occurredAt, raisedBySystem);
    }

    public void ChangeStatus(IncidentStatus newStatus, DateTimeOffset occurredAt, bool raisedBySystem = false)
    {
        if (newStatus == Status)
            return;

        if (newStatus == IncidentStatus.Investigating && !HasOwner && TeamId is null)
            throw new DomainException("An incident cannot enter Investigating without an owner or a responsible team.");

        var previousStatus = Status;
        Status = newStatus;

        if (newStatus == IncidentStatus.Resolved)
        {
            ResolvedAt = occurredAt;
        }
        else if (newStatus != IncidentStatus.Closed)
        {
            ResolvedAt = null;
        }

        AppendTimelineEvent(TimelineEventType.StatusChanged, $"Status changed from {previousStatus} to {newStatus}.", occurredAt, raisedBySystem);
    }

    public IncidentParticipant AddParticipant(
        Guid id,
        Guid userId,
        ParticipantRole role,
        DateTimeOffset assignedAt,
        Guid assignedById)
    {
        var participant = new IncidentParticipant(id, Id, userId, role, assignedAt, assignedById);
        _participants.Add(participant);

        if (role == ParticipantRole.Owner)
            AppendTimelineEvent(TimelineEventType.OwnerAssigned, "Owner assigned.", assignedAt, raisedBySystem: false);

        return participant;
    }

    public Comment AddComment(Guid id, Guid authorId, string text, DateTimeOffset writtenAt)
    {
        var comment = new Comment(id, Id, authorId, text, writtenAt);
        _comments.Add(comment);
        AppendTimelineEvent(TimelineEventType.CommentPosted, "Comment posted.", writtenAt, raisedBySystem: false);
        return comment;
    }

    public Subscription Subscribe(Guid id, Guid userId, NotificationChannel channel, DateTimeOffset subscribedAt)
    {
        var subscription = new Subscription(id, Id, userId, channel, subscribedAt);
        _subscriptions.Add(subscription);
        return subscription;
    }

    public PostmortemReport CreatePostmortemReport(
        Guid id,
        string summary,
        DateTimeOffset generatedAt,
        ReportFile? file = null)
    {
        if (Status != IncidentStatus.Resolved && Status != IncidentStatus.Closed)
            throw new DomainException("A postmortem report can only be created after the incident is Resolved or Closed.");

        if (Postmortem is not null)
            throw new DomainException("An incident can have at most one postmortem report.");

        Postmortem = new PostmortemReport(id, Id, summary, generatedAt, file);
        return Postmortem;
    }

    public ActionItem CreateActionItem(
        Guid id,
        string description,
        Guid assigneeId,
        DateTimeOffset dueAt,
        DateTimeOffset occurredAt,
        bool raisedBySystem = false)
    {
        if (Postmortem is null)
            throw new DomainException("An action item can only be created once the incident has a postmortem report.");

        var actionItem = new ActionItem(id, Id, description, assigneeId, dueAt);
        _actionItems.Add(actionItem);
        AppendTimelineEvent(TimelineEventType.ActionItemCreated, "Action item created.", occurredAt, raisedBySystem);
        return actionItem;
    }

    public void ReassignActionItem(Guid actionItemId, Guid newAssigneeId, DateTimeOffset occurredAt, bool raisedBySystem = false)
    {
        var actionItem = FindActionItem(actionItemId);

        actionItem.Reassign(newAssigneeId);
        AppendTimelineEvent(TimelineEventType.ActionItemReassigned, "Action item reassigned.", occurredAt, raisedBySystem);
    }

    public void ChangeActionItemStatus(Guid actionItemId, ActionItemStatus newStatus, DateTimeOffset occurredAt, bool raisedBySystem = false)
    {
        var actionItem = FindActionItem(actionItemId);
        if (actionItem.Status == newStatus)
            return;

        actionItem.ChangeStatus(newStatus);
        AppendTimelineEvent(TimelineEventType.ActionItemStatusChanged, $"Action item status changed to {newStatus}.", occurredAt, raisedBySystem);
    }

    private ActionItem FindActionItem(Guid actionItemId)
    {
        var actionItem = _actionItems.FirstOrDefault(a => a.Id == actionItemId);
        if (actionItem is null)
            throw new DomainException("Action item not found on this incident.");

        return actionItem;
    }

    private void AppendTimelineEvent(TimelineEventType type, string message, DateTimeOffset occurredAt, bool raisedBySystem)
    {
        _timelineEvents.Add(new TimelineEvent(Guid.NewGuid(), Id, type, message, occurredAt, raisedBySystem));
    }
}
