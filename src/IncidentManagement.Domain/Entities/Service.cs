using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Entities;

public sealed class Service : Entity
{
    public string Name { get; }
    public string Description { get; }

    public Service(Guid id, string name, string description) : base(id)
    {
        if (string.IsNullOrWhiteSpace(name))
            throw new ArgumentException("Name cannot be empty.", nameof(name));

        Name = name;
        Description = description ?? string.Empty;
    }
}
