using System.Linq;

namespace IncidentManagement.Domain.ValueObjects;

public sealed record Role
{
    private readonly HashSet<Permission> _permissions;

    public string Name { get; }
    public string Description { get; }
    public IReadOnlyCollection<Permission> Permissions => _permissions;

    public Role(string name, string description, IEnumerable<Permission>? permissions = null)
    {
        if (string.IsNullOrWhiteSpace(name))
            throw new ArgumentException("Name cannot be empty.", nameof(name));

        Name = name;
        Description = description ?? string.Empty;
        _permissions = permissions is null ? new HashSet<Permission>() : new HashSet<Permission>(permissions);
    }

    /// <summary>
    /// Hand-written because the compiler-generated record equality would
    /// compare <see cref="_permissions"/> by reference, not by content.
    /// </summary>
    public bool Equals(Role? other) =>
        other is not null
        && Name == other.Name
        && Description == other.Description
        && _permissions.SetEquals(other._permissions);

    public override int GetHashCode()
    {
        var hash = new HashCode();
        hash.Add(Name);
        hash.Add(Description);
        foreach (var permission in _permissions.OrderBy(p => p.Code, StringComparer.Ordinal))
            hash.Add(permission);
        return hash.ToHashCode();
    }
}
