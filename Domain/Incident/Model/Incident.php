<?php

declare(strict_types=1);

namespace Domain\Incident\Model;

use DateTime;
use Domain\AuditRecord\Model\AuditRecordCollection;
use Domain\Incident\Model\Status\IncidentStatus;
use Domain\IncidentParticipant\Model\IncidentParticipantCollection;
use Domain\PostmortemReport\Model\PostmortemReport;
use Domain\Service\Model\ServiceCollection;
use Domain\Subscription\Model\NotificationCollection;
use Domain\Subscription\Model\SubscriptionCollection;
use Domain\TimelineEvent\Model\TimelineEventCollection;

class Incident
{
    private string $id;
    private string $title;
    private string $description;
    private IncidentStatus $status;
    private Severity $severity;
    private DateTime $declaredAt;
    private ?DateTime $resolvedAt = null;
    private CommentCollection $comments;
    private IncidentParticipantCollection $participants;
    private ?PostmortemReport $postmortemReport = null;
    private NotificationCollection $notifications;
    private SubscriptionCollection $subscriptions;
    private TimelineEventCollection $timelineEvents;
    private ServiceCollection $services;
    private AuditRecordCollection $auditRecords;

    public function __construct(
        string $id,
        string $title,
        string $description,
        IncidentStatus $status,
        Severity $severity,
        DateTime $declaredAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->severity = $severity;
        $this->declaredAt = $declaredAt;
        $this->comments = new CommentCollection();
        $this->participants = new IncidentParticipantCollection();
        $this->notifications = new NotificationCollection();
        $this->subscriptions = new SubscriptionCollection();
        $this->timelineEvents = new TimelineEventCollection();
        $this->services = new ServiceCollection();
        $this->auditRecords = new AuditRecordCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): IncidentStatus
    {
        return $this->status;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getDeclaredAt(): DateTime
    {
        return $this->declaredAt;
    }

    public function getResolvedAt(): ?DateTime
    {
        return $this->resolvedAt;
    }

    public function getComments(): CommentCollection
    {
        return $this->comments;
    }

    public function getParticipants(): IncidentParticipantCollection
    {
        return $this->participants;
    }

    public function getPostmortemReport(): ?PostmortemReport
    {
        return $this->postmortemReport;
    }

    public function setPostmortemReport(PostmortemReport $postmortemReport): void
    {
        $this->postmortemReport = $postmortemReport;
    }

    public function getNotifications(): NotificationCollection
    {
        return $this->notifications;
    }

    public function getSubscriptions(): SubscriptionCollection
    {
        return $this->subscriptions;
    }

    public function getTimelineEvents(): TimelineEventCollection
    {
        return $this->timelineEvents;
    }

    public function getServices(): ServiceCollection
    {
        return $this->services;
    }

    public function getAuditRecords(): AuditRecordCollection
    {
        return $this->auditRecords;
    }
}