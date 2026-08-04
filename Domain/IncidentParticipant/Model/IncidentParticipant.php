<?php

declare(strict_types=1);

namespace Domain\IncidentParticipant\Model;

use DateTime;
use Domain\Incident\Model\Incident;
use Domain\User\Model\User;

class IncidentParticipant
{
    private string $id;
    private ParticipantRole $role;
    private DateTime $assignedAt;
    private User $assignedBy;
    private User $assignedTo;
    private Incident $incident;

    public function __construct(
        string $id,
        ParticipantRole $role,
        DateTime $assignedAt,
        User $assignedBy,
        User $assignedTo,
        Incident $incident
    ) {
        $this->id = $id;
        $this->role = $role;
        $this->assignedAt = $assignedAt;
        $this->assignedBy = $assignedBy;
        $this->assignedTo = $assignedTo;
        $this->incident = $incident;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRole(): ParticipantRole
    {
        return $this->role;
    }

    public function getAssignedAt(): DateTime
    {
        return $this->assignedAt;
    }

    public function getAssignedBy(): User
    {
        return $this->assignedBy;
    }

    public function getAssignedTo(): User
    {
        return $this->assignedTo;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }
}