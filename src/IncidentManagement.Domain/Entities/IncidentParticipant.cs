using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;

namespace IncidentManagement.Domain.Entities;

public sealed class IncidentParticipant : Entity
{
    public Guid IncidentId { get; }
    public Guid UserId { get; }
    public ParticipantRole Role { get; }
    public DateTimeOffset AssignedAt { get; }
    public Guid AssignedById { get; }

    public IncidentParticipant(
        Guid id,
        Guid incidentId,
        Guid userId,
        ParticipantRole role,
        DateTimeOffset assignedAt,
        Guid assignedById) : base(id)
    {
        IncidentId = incidentId;
        UserId = userId;
        Role = role;
        AssignedAt = assignedAt;
        AssignedById = assignedById;
    }
}
