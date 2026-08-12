using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Entities;

public sealed class Team : Entity
{
    public string Name { get; }

    public Team(Guid id, string name) : base(id)
    {
        if (string.IsNullOrWhiteSpace(name))
            throw new ArgumentException("Name cannot be empty.", nameof(name));

        Name = name;
    }
}
