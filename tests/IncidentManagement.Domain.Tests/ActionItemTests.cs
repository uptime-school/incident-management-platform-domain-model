using System.Linq;
using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Entities;
using IncidentManagement.Domain.Enums;
using Xunit;

namespace IncidentManagement.Domain.Tests;

public class ActionItemTests
{
    private static Incident NewResolvedIncidentWithPostmortem()
    {
        var incident = new Incident(
            Guid.NewGuid(),
            "Payments API returning 500s",
            "Elevated error rate on checkout.",
            Severity.Sev2,
            DateTimeOffset.UtcNow);

        incident.AddParticipant(Guid.NewGuid(), Guid.NewGuid(), ParticipantRole.Owner, DateTimeOffset.UtcNow, Guid.NewGuid());
        incident.ChangeStatus(IncidentStatus.Investigating, DateTimeOffset.UtcNow);
        incident.ChangeStatus(IncidentStatus.Resolved, DateTimeOffset.UtcNow);
        incident.CreatePostmortemReport(Guid.NewGuid(), "Root cause analysis.", DateTimeOffset.UtcNow);

        return incident;
    }

    [Fact]
    public void CreateActionItem_BeforePostmortem_Throws()
    {
        var incident = new Incident(
            Guid.NewGuid(),
            "Payments API returning 500s",
            "Elevated error rate on checkout.",
            Severity.Sev2,
            DateTimeOffset.UtcNow);

        Assert.Throws<DomainException>(() =>
            incident.CreateActionItem(Guid.NewGuid(), "Add retry budget alerting.", Guid.NewGuid(), DateTimeOffset.UtcNow.AddDays(7), DateTimeOffset.UtcNow));
    }

    [Fact]
    public void CreateActionItem_AfterPostmortem_Succeeds_DefaultsToToDo_AndAppendsOneTimelineEvent()
    {
        var incident = NewResolvedIncidentWithPostmortem();
        var countBefore = incident.TimelineEvents.Count;
        var assigneeId = Guid.NewGuid();
        var dueAt = DateTimeOffset.UtcNow.AddDays(7);

        var actionItem = incident.CreateActionItem(Guid.NewGuid(), "Add retry budget alerting.", assigneeId, dueAt, DateTimeOffset.UtcNow);

        Assert.Equal(ActionItemStatus.ToDo, actionItem.Status);
        Assert.Equal(assigneeId, actionItem.AssigneeId);
        Assert.Equal(dueAt, actionItem.DueAt);
        Assert.Contains(actionItem, incident.ActionItems);
        Assert.Equal(countBefore + 1, incident.TimelineEvents.Count);
        Assert.Equal(TimelineEventType.ActionItemCreated, incident.TimelineEvents.Last().Type);
    }

    [Fact]
    public void ReassignActionItem_ChangesAssignee_AndAppendsOneTimelineEvent()
    {
        var incident = NewResolvedIncidentWithPostmortem();
        var actionItem = incident.CreateActionItem(Guid.NewGuid(), "Add retry budget alerting.", Guid.NewGuid(), DateTimeOffset.UtcNow.AddDays(7), DateTimeOffset.UtcNow);
        var countBefore = incident.TimelineEvents.Count;
        var newAssigneeId = Guid.NewGuid();

        incident.ReassignActionItem(actionItem.Id, newAssigneeId, DateTimeOffset.UtcNow);

        Assert.Equal(newAssigneeId, actionItem.AssigneeId);
        Assert.Equal(countBefore + 1, incident.TimelineEvents.Count);
        Assert.Equal(TimelineEventType.ActionItemReassigned, incident.TimelineEvents.Last().Type);
    }

    [Fact]
    public void ChangeActionItemStatus_ToDifferentStatus_AppendsOneTimelineEvent()
    {
        var incident = NewResolvedIncidentWithPostmortem();
        var actionItem = incident.CreateActionItem(Guid.NewGuid(), "Add retry budget alerting.", Guid.NewGuid(), DateTimeOffset.UtcNow.AddDays(7), DateTimeOffset.UtcNow);
        var countBefore = incident.TimelineEvents.Count;

        incident.ChangeActionItemStatus(actionItem.Id, ActionItemStatus.InProgress, DateTimeOffset.UtcNow);

        Assert.Equal(ActionItemStatus.InProgress, actionItem.Status);
        Assert.Equal(countBefore + 1, incident.TimelineEvents.Count);
        Assert.Equal(TimelineEventType.ActionItemStatusChanged, incident.TimelineEvents.Last().Type);
    }

    [Fact]
    public void ChangeActionItemStatus_ToSameStatus_IsNoOpAndAppendsNoEvent()
    {
        var incident = NewResolvedIncidentWithPostmortem();
        var actionItem = incident.CreateActionItem(Guid.NewGuid(), "Add retry budget alerting.", Guid.NewGuid(), DateTimeOffset.UtcNow.AddDays(7), DateTimeOffset.UtcNow);
        var countBefore = incident.TimelineEvents.Count;

        incident.ChangeActionItemStatus(actionItem.Id, ActionItemStatus.ToDo, DateTimeOffset.UtcNow);

        Assert.Equal(countBefore, incident.TimelineEvents.Count);
    }

    [Fact]
    public void ReassignActionItem_UnknownId_Throws()
    {
        var incident = NewResolvedIncidentWithPostmortem();

        Assert.Throws<DomainException>(() =>
            incident.ReassignActionItem(Guid.NewGuid(), Guid.NewGuid(), DateTimeOffset.UtcNow));
    }

    [Fact]
    public void ChangeActionItemStatus_UnknownId_Throws()
    {
        var incident = NewResolvedIncidentWithPostmortem();

        Assert.Throws<DomainException>(() =>
            incident.ChangeActionItemStatus(Guid.NewGuid(), ActionItemStatus.Done, DateTimeOffset.UtcNow));
    }
}
