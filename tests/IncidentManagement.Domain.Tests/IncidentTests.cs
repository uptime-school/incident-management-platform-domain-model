using System.Linq;
using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Entities;
using IncidentManagement.Domain.Enums;
using Xunit;

namespace IncidentManagement.Domain.Tests;

public class IncidentTests
{
    private static Incident NewIncident(Guid? teamId = null) =>
        new(
            Guid.NewGuid(),
            "Payments API returning 500s",
            "Elevated error rate on checkout.",
            Severity.Sev2,
            DateTimeOffset.UtcNow,
            teamId);

    private static void AddOwner(Incident incident) =>
        incident.AddParticipant(Guid.NewGuid(), Guid.NewGuid(), ParticipantRole.Owner, DateTimeOffset.UtcNow, Guid.NewGuid());

    [Fact]
    public void ChangeStatus_ToInvestigating_WithoutOwnerOrTeam_Throws()
    {
        var incident = NewIncident();

        var ex = Assert.Throws<DomainException>(
            () => incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow));

        Assert.Contains("owner or a responsible team", ex.Message);
    }

    [Theory]
    [InlineData(true, false)]
    [InlineData(false, true)]
    public void ChangeStatus_ToInvestigating_WithOwnerOrTeam_Succeeds(bool withOwner, bool withTeam)
    {
        var incident = NewIncident(teamId: withTeam ? Guid.NewGuid() : null);
        if (withOwner)
            AddOwner(incident);

        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);

        Assert.Equal(IncidentStatus.Investigating, incident.Status);
    }

    [Fact]
    public void AddParticipant_WithOwnerRole_IsReflectedInOwnerAndHasOwner()
    {
        var incident = NewIncident();

        AddOwner(incident);

        Assert.True(incident.HasOwner);
        Assert.NotNull(incident.Owner);
        Assert.Equal(ParticipantRole.Owner, incident.Owner!.Role);
    }

    [Fact]
    public void ChangeStatus_ToResolved_SetsResolvedAt()
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);

        var resolvedAt = DateTimeOffset.UtcNow;
        incident.ChangeStatus(IncidentStatus.Resolved, resolvedAt);

        Assert.Equal(resolvedAt, incident.ResolvedAt);
    }

    [Fact]
    public void ChangeStatus_ReopenAfterResolved_ClearsResolvedAt()
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        incident.ChangeStatus(IncidentStatus.Resolved, DateTimeOffset.UtcNow);

        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);

        Assert.Null(incident.ResolvedAt);
    }

    [Fact]
    public void ChangeStatus_ToClosed_KeepsResolvedAt()
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        var resolvedAt = DateTimeOffset.UtcNow;
        incident.ChangeStatus(IncidentStatus.Resolved, resolvedAt);

        incident.ChangeStatus(IncidentStatus.Closed, DateTimeOffset.UtcNow);

        Assert.Equal(resolvedAt, incident.ResolvedAt);
    }

    [Fact]
    public void Constructor_AppendsExactlyOneTimelineEvent()
    {
        var incident = NewIncident();

        Assert.Single(incident.TimelineEvents);
        Assert.Equal(TimelineEventType.StatusChanged, incident.TimelineEvents.Single().Type);
    }

    [Fact]
    public void EachStateChange_AppendsExactlyOneTimelineEvent()
    {
        var incident = NewIncident();
        var countAfterCreate = incident.TimelineEvents.Count;

        AddOwner(incident);
        Assert.Equal(countAfterCreate + 1, incident.TimelineEvents.Count);

        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        Assert.Equal(countAfterCreate + 2, incident.TimelineEvents.Count);

        incident.ChangeSeverity(Severity.Sev1, DateTimeOffset.UtcNow);
        Assert.Equal(countAfterCreate + 3, incident.TimelineEvents.Count);

        incident.AddComment(Guid.NewGuid(), Guid.NewGuid(), "Investigating now.", DateTimeOffset.UtcNow);
        Assert.Equal(countAfterCreate + 4, incident.TimelineEvents.Count);
    }

    [Fact]
    public void ChangeStatus_ToSameStatus_IsNoOpAndAppendsNoEvent()
    {
        var incident = NewIncident();
        var countBefore = incident.TimelineEvents.Count;

        incident.ChangeStatus(IncidentStatus.Open, DateTimeOffset.UtcNow);

        Assert.Equal(countBefore, incident.TimelineEvents.Count);
    }

    [Fact]
    public void CreatePostmortemReport_BeforeResolvedOrClosed_Throws()
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);

        Assert.Throws<DomainException>(
            () => incident.CreatePostmortemReport(Guid.NewGuid(), "Root cause analysis.", DateTimeOffset.UtcNow));
    }

    [Theory]
    [InlineData(IncidentStatus.Resolved)]
    [InlineData(IncidentStatus.Closed)]
    public void CreatePostmortemReport_AfterResolvedOrClosed_Succeeds(IncidentStatus status)
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        incident.ChangeStatus(IncidentStatus.Resolved, DateTimeOffset.UtcNow);
        if (status == IncidentStatus.Closed)
            incident.ChangeStatus(IncidentStatus.Closed, DateTimeOffset.UtcNow);

        var report = incident.CreatePostmortemReport(Guid.NewGuid(), "Root cause analysis.", DateTimeOffset.UtcNow);

        Assert.Same(report, incident.Postmortem);
    }

    [Fact]
    public void CreatePostmortemReport_Twice_Throws()
    {
        var incident = NewIncident();
        AddOwner(incident);
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        incident.ChangeStatus(IncidentStatus.Resolved, DateTimeOffset.UtcNow);
        incident.CreatePostmortemReport(Guid.NewGuid(), "First report.", DateTimeOffset.UtcNow);

        Assert.Throws<DomainException>(
            () => incident.CreatePostmortemReport(Guid.NewGuid(), "Second report.", DateTimeOffset.UtcNow));
    }
}
