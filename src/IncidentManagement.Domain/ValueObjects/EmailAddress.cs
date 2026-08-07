namespace IncidentManagement.Domain.ValueObjects;

public sealed record EmailAddress
{
    public string Value { get; }

    public EmailAddress(string value)
    {
        if (string.IsNullOrWhiteSpace(value) || !value.Contains('@'))
            throw new ArgumentException("Value is not a valid email address.", nameof(value));

        Value = value;
    }

    public override string ToString() => Value;
}
