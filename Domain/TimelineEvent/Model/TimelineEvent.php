<?php

declare(strict_types=1);

namespace Domain\TimelineEvent\Model;

use DateTime;
use Domain\Incident\Model\Incident;
use Domain\User\Model\User;

class TimelineEvent
{
    private string $id;
    private TimelineEventType $type;
    private string $message;
    private DateTime $occurredAt;
    private bool $raisedBySystem;
    private Incident $incident;
    private ?User $author = null;

    public function __construct(
        string $id,
        TimelineEventType $type,
        string $message,
        DateTime $occurredAt,
        bool $raisedBySystem,
        Incident $incident,
        ?User $author = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;
        $this->occurredAt = $occurredAt;
        $this->raisedBySystem = $raisedBySystem;
        $this->incident = $incident;
        $this->author = $author;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): TimelineEventType
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getOccurredAt(): DateTime
    {
        return $this->occurredAt;
    }

    public function isRaisedBySystem(): bool
    {
        return $this->raisedBySystem;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }
}