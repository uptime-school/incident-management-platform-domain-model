using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Users.Errors;

public static class UserErrors
{
    public static readonly Error NameRequired = Error.Validation(
        "Users.NameRequired",
        "User name is required.");

    public static readonly Error EmptyEmail = Error.Validation(
        "Users.EmptyEmail",
        "Email address cannot be empty.");

    public static readonly Error InvalidEmail = Error.Validation(
        "Users.InvalidEmail",
        "Email address is invalid.");
}
