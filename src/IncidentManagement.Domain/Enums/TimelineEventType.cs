namespace IncidentManagement.Domain.Enums;

public enum TimelineEventType
{
    StatusChanged,
    SeverityChanged,
    OwnerAssigned,
    Acknowledged,
    CommentPosted,
    SystemEvent,
    ActionItemCreated,
    ActionItemReassigned,
    ActionItemStatusChanged
}
