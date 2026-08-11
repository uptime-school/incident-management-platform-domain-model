using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.ValueObjects;

namespace IncidentManagement.Domain.Entities;

public sealed class User : Entity
{
    private readonly HashSet<Guid> _teamIds = new();

    public string FullName { get; }
    public EmailAddress Email { get; }
    public IReadOnlyCollection<Guid> TeamIds => _teamIds;

    public User(Guid id, string fullName, EmailAddress email) : base(id)
    {
        if (string.IsNullOrWhiteSpace(fullName))
            throw new ArgumentException("Full name cannot be empty.", nameof(fullName));

        FullName = fullName;
        Email = email ?? throw new ArgumentNullException(nameof(email));
    }

    public void JoinTeam(Guid teamId) => _teamIds.Add(teamId);

    public void LeaveTeam(Guid teamId) => _teamIds.Remove(teamId);
}
