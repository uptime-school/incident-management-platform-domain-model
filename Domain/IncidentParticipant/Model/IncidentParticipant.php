<?php

declare(strict_types=1);

class IncidentParticipant
{
    private string $id;
    private ParticipantRole $role;
    private DateTime $assignedAt;
    private User $assignedBy;
    private User $assignedTo;
    private Incident $incident;
}