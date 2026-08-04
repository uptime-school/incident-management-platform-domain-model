<?php

declare(strict_types=1);

namespace Domain\AuditRecord\Model;

use DateTime;
use Domain\Incident\Model\Incident;
use Domain\User\Model\User;

class AuditRecord
{
    private string $id;
    private AuditAction $action;
    private string $previousValue;
    private string $newValue;
    private DateTime $recordedAt;
    private User $performedBy;
    private Incident $incident;

    public function __construct(
        string $id,
        AuditAction $action,
        string $previousValue,
        string $newValue,
        DateTime $recordedAt,
        User $performedBy,
        Incident $incident
    ) {
        $this->id = $id;
        $this->action = $action;
        $this->previousValue = $previousValue;
        $this->newValue = $newValue;
        $this->recordedAt = $recordedAt;
        $this->performedBy = $performedBy;
        $this->incident = $incident;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAction(): AuditAction
    {
        return $this->action;
    }

    public function getPreviousValue(): string
    {
        return $this->previousValue;
    }

    public function getNewValue(): string
    {
        return $this->newValue;
    }

    public function getRecordedAt(): DateTime
    {
        return $this->recordedAt;
    }

    public function getPerformedBy(): User
    {
        return $this->performedBy;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }
}
