using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;
using IncidentManagement.Domain.ValueObjects;

namespace IncidentManagement.Domain.Entities;

public sealed class AuditRecord : Entity
{
    public AuditAction Action { get; }
    public string PreviousValue { get; }
    public string NewValue { get; }
    public DateTimeOffset RecordedAt { get; }
    public Guid PerformedByUserId { get; }
    public Role? GrantedRole { get; }

    public AuditRecord(
        Guid id,
        AuditAction action,
        string previousValue,
        string newValue,
        DateTimeOffset recordedAt,
        Guid performedByUserId,
        Role? grantedRole = null) : base(id)
    {
        Action = action;
        PreviousValue = previousValue ?? string.Empty;
        NewValue = newValue ?? string.Empty;
        RecordedAt = recordedAt;
        PerformedByUserId = performedByUserId;
        GrantedRole = grantedRole;
    }
}
