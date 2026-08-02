namespace IncidentManagement.Domain.Incidents.ValueObjects;

public readonly record struct IncidentId(string Value)
{
    public static IncidentId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
