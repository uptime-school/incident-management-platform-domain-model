namespace IncidentManagement.Domain.IncidentParticipants.ValueObjects;

public readonly record struct IncidentParticipantId(string Value)
{
    public static IncidentParticipantId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
