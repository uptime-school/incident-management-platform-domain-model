using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Entities;
using IncidentManagement.Domain.Enums;
using IncidentManagement.Domain.ValueObjects;
using Xunit;

namespace IncidentManagement.Domain.Tests;

public class SupportingEntityTests
{
    [Fact]
    public void User_JoinTeam_AddsTeamId_AndIsIdempotent()
    {
        var user = new User(Guid.NewGuid(), "Jamie Rivera", new EmailAddress("jamie@example.com"));
        var teamId = Guid.NewGuid();

        user.JoinTeam(teamId);
        user.JoinTeam(teamId);

        Assert.Single(user.TeamIds);
        Assert.Contains(teamId, user.TeamIds);
    }

    [Fact]
    public void User_LeaveTeam_RemovesTeamId()
    {
        var user = new User(Guid.NewGuid(), "Jamie Rivera", new EmailAddress("jamie@example.com"));
        var teamId = Guid.NewGuid();
        user.JoinTeam(teamId);

        user.LeaveTeam(teamId);

        Assert.Empty(user.TeamIds);
    }

    [Fact]
    public void Incident_AddAffectedService_AddsServiceId()
    {
        var incident = new Incident(Guid.NewGuid(), "Checkout down", "desc", Severity.Sev1, DateTimeOffset.UtcNow);
        var serviceId = Guid.NewGuid();

        incident.AddAffectedService(serviceId);

        Assert.Contains(serviceId, incident.AffectedServiceIds);
    }

    [Fact]
    public void Incident_SendNotification_RequiresExistingComment()
    {
        var incident = new Incident(Guid.NewGuid(), "Checkout down", "desc", Severity.Sev1, DateTimeOffset.UtcNow);

        Assert.Throws<DomainException>(() =>
            incident.SendNotification(Guid.NewGuid(), Guid.NewGuid(), Guid.NewGuid(), NotificationChannel.Email, DateTimeOffset.UtcNow));
    }

    [Fact]
    public void Incident_SendNotification_ForExistingComment_Succeeds()
    {
        var incident = new Incident(Guid.NewGuid(), "Checkout down", "desc", Severity.Sev1, DateTimeOffset.UtcNow);
        var comment = incident.AddComment(Guid.NewGuid(), Guid.NewGuid(), "Looking into it.", DateTimeOffset.UtcNow);
        var recipientId = Guid.NewGuid();

        var notification = incident.SendNotification(Guid.NewGuid(), comment.Id, recipientId, NotificationChannel.Sms, DateTimeOffset.UtcNow);

        Assert.Contains(notification, incident.Notifications);
        Assert.Equal(recipientId, notification.RecipientUserId);
    }

    [Fact]
    public void Team_RejectsEmptyName()
    {
        Assert.Throws<ArgumentException>(() => new Team(Guid.NewGuid(), " "));
    }

    [Fact]
    public void Service_RejectsEmptyName()
    {
        Assert.Throws<ArgumentException>(() => new Service(Guid.NewGuid(), "", "desc"));
    }

    [Fact]
    public void Role_RejectsEmptyName()
    {
        Assert.Throws<ArgumentException>(() => new Role("", "desc"));
    }

    [Fact]
    public void Permission_RejectsEmptyCode()
    {
        Assert.Throws<ArgumentException>(() => new Permission("", "desc"));
    }

    [Fact]
    public void Role_StoresGrantedPermissions()
    {
        var editIncidents = new Permission("incidents.edit", "Edit incidents");
        var closeIncidents = new Permission("incidents.close", "Close incidents");

        var role = new Role("Incident Manager", "Can manage incidents", new[] { editIncidents, closeIncidents });

        Assert.Contains(editIncidents, role.Permissions);
        Assert.Contains(closeIncidents, role.Permissions);
    }

    [Fact]
    public void Role_Equality_IsValueBased_RegardlessOfPermissionOrder()
    {
        var editIncidents = new Permission("incidents.edit", "Edit incidents");
        var closeIncidents = new Permission("incidents.close", "Close incidents");

        var roleA = new Role("Incident Manager", "Can manage incidents", new[] { editIncidents, closeIncidents });
        var roleB = new Role("Incident Manager", "Can manage incidents", new[] { closeIncidents, editIncidents });

        Assert.Equal(roleA, roleB);
        Assert.Equal(roleA.GetHashCode(), roleB.GetHashCode());
    }

    [Fact]
    public void Role_Equality_DiffersWhenPermissionSetsDiffer()
    {
        var editIncidents = new Permission("incidents.edit", "Edit incidents");
        var closeIncidents = new Permission("incidents.close", "Close incidents");

        var roleA = new Role("Incident Manager", "Can manage incidents", new[] { editIncidents });
        var roleB = new Role("Incident Manager", "Can manage incidents", new[] { editIncidents, closeIncidents });

        Assert.NotEqual(roleA, roleB);
    }

    [Fact]
    public void AuditRecord_StoresGrantedRole_WhenProvided()
    {
        var role = new Role("Incident Manager", "Can manage incidents");

        var record = new AuditRecord(
            Guid.NewGuid(),
            AuditAction.PermissionChanged,
            previousValue: "Responder",
            newValue: "Incident Manager",
            recordedAt: DateTimeOffset.UtcNow,
            performedByUserId: Guid.NewGuid(),
            grantedRole: role);

        Assert.Equal(role, record.GrantedRole);
    }
}
