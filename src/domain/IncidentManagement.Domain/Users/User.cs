using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Users.Errors;
using IncidentManagement.Domain.Users.ValueObjects;

namespace IncidentManagement.Domain.Users;

public sealed class User : AggregateRoot<UserId>
{
    private User()
    {
    }

    public string Name { get; private set; } = string.Empty;

    public EmailAddress Email { get; private set; } = null!;

    public static Result<User> Create(UserId id, string name, EmailAddress email)
    {
        if (string.IsNullOrWhiteSpace(name))
        {
            return Result<User>.Failure<User>(UserErrors.NameRequired);
        }

        var user = new User
        {
            Id = id,
            Name = name.Trim(),
            Email = email
        };

        return Result<User>.Success(user);
    }
}
