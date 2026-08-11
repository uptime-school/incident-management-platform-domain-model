namespace IncidentManagement.Domain.ValueObjects;

public sealed record Permission
{
    public string Code { get; }
    public string Description { get; }

    public Permission(string code, string description)
    {
        if (string.IsNullOrWhiteSpace(code))
            throw new ArgumentException("Code cannot be empty.", nameof(code));

        Code = code;
        Description = description ?? string.Empty;
    }
}
