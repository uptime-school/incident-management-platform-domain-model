namespace IncidentManagement.Domain.Subscriptions.ValueObjects;

public readonly record struct SubscriptionId(string Value)
{
    public static SubscriptionId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
