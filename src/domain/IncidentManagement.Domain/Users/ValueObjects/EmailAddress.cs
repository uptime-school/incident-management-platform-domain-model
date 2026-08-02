using System.Net.Mail;
using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Users.Errors;

namespace IncidentManagement.Domain.Users.ValueObjects;

public sealed record EmailAddress
{
    private EmailAddress(string value)
    {
        Value = value;
    }

    public string Value { get; }

    public static Result<EmailAddress> Create(string value)
    {
        if (string.IsNullOrWhiteSpace(value))
        {
            return Result<EmailAddress>.Failure<EmailAddress>(UserErrors.EmptyEmail);
        }

        if (!MailAddress.TryCreate(value.Trim(), out MailAddress? address))
        {
            return Result<EmailAddress>.Failure<EmailAddress>(UserErrors.InvalidEmail);
        }

        return Result<EmailAddress>.Success(new EmailAddress(address.Address));
    }

    public override string ToString() => Value;

    public static implicit operator string(EmailAddress emailAddress) => emailAddress.Value;
}
