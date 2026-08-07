using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;

namespace IncidentManagement.Domain.Entities;

public sealed class ActionItem : Entity
{
    public Guid IncidentId { get; }
    public string Description { get; }
    public Guid AssigneeId { get; private set; }
    public DateTimeOffset DueAt { get; }
    public ActionItemStatus Status { get; private set; }

    internal ActionItem(Guid id, Guid incidentId, string description, Guid assigneeId, DateTimeOffset dueAt) : base(id)
    {
        if (string.IsNullOrWhiteSpace(description))
            throw new ArgumentException("Description cannot be empty.", nameof(description));

        IncidentId = incidentId;
        Description = description;
        AssigneeId = assigneeId;
        DueAt = dueAt;
        Status = ActionItemStatus.ToDo;
    }

    internal void Reassign(Guid assigneeId)
    {
        AssigneeId = assigneeId;
    }

    internal void ChangeStatus(ActionItemStatus status)
    {
        Status = status;
    }
}
