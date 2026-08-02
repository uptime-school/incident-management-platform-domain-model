using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.IncidentParticipants.ValueObjects;
using IncidentManagement.Domain.Users.ValueObjects;

namespace IncidentManagement.Domain.IncidentParticipants;

public sealed class IncidentParticipant
{
    private IncidentParticipant()
    {
    }

    public IncidentParticipantId Id { get; private set; }

    public UserId UserId { get; private set; }

    public ParticipantRole Role { get; private set; }

    public DateTimeOffset AssignedAt { get; private set; }

    public UserId AssignedByUserId { get; private set; }

    public static Result<IncidentParticipant> Create(
        IncidentParticipantId id,
        UserId userId,
        ParticipantRole role,
        UserId assignedByUserId,
        DateTimeOffset assignedAt)
    {
        var participant = new IncidentParticipant
        {
            Id = id,
            UserId = userId,
            Role = role,
            AssignedByUserId = assignedByUserId,
            AssignedAt = assignedAt
        };

        return Result<IncidentParticipant>.Success(participant);
    }
}
