using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.TimelineEvents.Errors;
using IncidentManagement.Domain.TimelineEvents.ValueObjects;

namespace IncidentManagement.Domain.TimelineEvents;

public sealed class TimelineEvent
{
    private TimelineEvent()
    {
    }

    public TimelineEventId Id { get; private set; }

    public TimelineEventType Type { get; private set; }

    public string Message { get; private set; } = string.Empty;

    public DateTimeOffset OccurredAt { get; private set; }

    public bool RaisedBySystem { get; private set; }

    public static Result<TimelineEvent> Create(
        TimelineEventId id,
        TimelineEventType type,
        string message,
        DateTimeOffset occurredAt,
        bool raisedBySystem)
    {
        if (string.IsNullOrWhiteSpace(message))
        {
            return Result<TimelineEvent>.Failure<TimelineEvent>(TimelineEventErrors.MessageRequired);
        }

        var timelineEvent = new TimelineEvent
        {
            Id = id,
            Type = type,
            Message = message.Trim(),
            OccurredAt = occurredAt,
            RaisedBySystem = raisedBySystem
        };

        return Result<TimelineEvent>.Success(timelineEvent);
    }
}
