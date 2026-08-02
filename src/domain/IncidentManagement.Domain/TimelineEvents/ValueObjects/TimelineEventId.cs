namespace IncidentManagement.Domain.TimelineEvents.ValueObjects;

public readonly record struct TimelineEventId(string Value)
{
    public static TimelineEventId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
