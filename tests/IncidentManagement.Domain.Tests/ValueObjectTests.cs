using IncidentManagement.Domain.ValueObjects;
using Xunit;

namespace IncidentManagement.Domain.Tests;

public class ValueObjectTests
{
    [Theory]
    [InlineData("")]
    [InlineData(" ")]
    [InlineData("not-an-email")]
    public void EmailAddress_RejectsInvalidValues(string value)
    {
        Assert.Throws<ArgumentException>(() => new EmailAddress(value));
    }

    [Fact]
    public void EmailAddress_AcceptsValidValue()
    {
        var email = new EmailAddress("oncall@example.com");

        Assert.Equal("oncall@example.com", email.Value);
    }

    [Fact]
    public void EmailAddress_EqualityIsValueBased()
    {
        Assert.Equal(new EmailAddress("a@b.com"), new EmailAddress("a@b.com"));
    }
}
