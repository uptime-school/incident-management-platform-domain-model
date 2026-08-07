using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;

namespace IncidentManagement.Domain.Entities;

public sealed class TimelineEvent : Entity
{
    public Guid IncidentId { get; }
    public TimelineEventType Type { get; }
    public string Message { get; }
    public DateTimeOffset OccurredAt { get; }
    public bool RaisedBySystem { get; }

    public TimelineEvent(
        Guid id,
        Guid incidentId,
        TimelineEventType type,
        string message,
        DateTimeOffset occurredAt,
        bool raisedBySystem) : base(id)
    {
        IncidentId = incidentId;
        Type = type;
        Message = message ?? string.Empty;
        OccurredAt = occurredAt;
        RaisedBySystem = raisedBySystem;
    }
}
