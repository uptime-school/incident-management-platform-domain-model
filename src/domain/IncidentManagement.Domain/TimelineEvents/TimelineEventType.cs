namespace IncidentManagement.Domain.TimelineEvents;

public enum TimelineEventType
{
    StatusChanged = 1,
    SeverityChanged = 2,
    OwnerAssigned = 3,
    Acknowledged = 4,
    CommentPosted = 5,
    SystemEvent = 6
}
