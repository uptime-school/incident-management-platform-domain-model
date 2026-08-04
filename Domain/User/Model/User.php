<?php

declare(strict_types=1);

namespace Domain\User\Model;

use Domain\Action\Model\ActionCollection;
use Domain\AuditRecord\Model\AuditRecordCollection;
use Domain\Incident\Model\CommentCollection;
use Domain\IncidentParticipant\Model\IncidentParticipantCollection;
use Domain\Role\Model\RoleCollection;
use Domain\Subscription\Model\SubscriptionCollection;
use Domain\Team\Model\TeamCollection;
use Domain\TimelineEvent\Model\TimelineEventCollection;

class User
{
    private string $id;
    private string $name;
    private EmailAddress $email;
    private SubscriptionCollection $subscriptions;
    private CommentCollection $comments;
    private IncidentParticipantCollection $participationsAssigned;
    private IncidentParticipantCollection $participationsReceived;
    private TimelineEventCollection $timelineEvents;
    private TeamCollection $teams;
    private RoleCollection $roles;
    private AuditRecordCollection $auditRecords;
    private ActionCollection $assignedActions;

    public function __construct(string $id, string $name, EmailAddress $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->subscriptions = new SubscriptionCollection();
        $this->comments = new CommentCollection();
        $this->participationsAssigned = new IncidentParticipantCollection();
        $this->participationsReceived = new IncidentParticipantCollection();
        $this->timelineEvents = new TimelineEventCollection();
        $this->teams = new TeamCollection();
        $this->roles = new RoleCollection();
        $this->auditRecords = new AuditRecordCollection();
        $this->assignedActions = new ActionCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getSubscriptions(): SubscriptionCollection
    {
        return $this->subscriptions;
    }

    public function getComments(): CommentCollection
    {
        return $this->comments;
    }

    public function getParticipationsAssigned(): IncidentParticipantCollection
    {
        return $this->participationsAssigned;
    }

    public function getParticipationsReceived(): IncidentParticipantCollection
    {
        return $this->participationsReceived;
    }

    public function getTimelineEvents(): TimelineEventCollection
    {
        return $this->timelineEvents;
    }

    public function getTeams(): TeamCollection
    {
        return $this->teams;
    }

    public function getRoles(): RoleCollection
    {
        return $this->roles;
    }

    public function getAuditRecords(): AuditRecordCollection
    {
        return $this->auditRecords;
    }

    public function getAssignedActions(): ActionCollection
    {
        return $this->assignedActions;
    }
}