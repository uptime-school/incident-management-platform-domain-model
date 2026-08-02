namespace IncidentManagement.Domain.Users.ValueObjects;

public readonly record struct UserId(string Value)
{
    public static UserId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
