<?php

declare(strict_types=1);

namespace App\Domain\IncidentParticipant\Model;

use DateTimeImmutable;
use App\Domain\Incident\Model\Incident;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IncidentParticipant extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(enumType: ParticipantRole::class)]
        private ParticipantRole $role,
        #[ORM\Column]
        private readonly DateTimeImmutable $assignedAt,
        #[ORM\Column(length: 36)]
        private readonly string $assignedById,
        #[ORM\Column(length: 36)]
        private readonly string $assignedToId,
        #[ORM\ManyToOne(targetEntity: Incident::class, inversedBy: 'participants')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Incident $incident
    ) {
        parent::__construct(new Id($id));
    }

    public function getRole(): ParticipantRole
    {
        return $this->role;
    }

    public function getAssignedAt(): DateTimeImmutable
    {
        return $this->assignedAt;
    }

    public function getAssignedById(): string
    {
        return $this->assignedById;
    }

    public function getAssignedToId(): string
    {
        return $this->assignedToId;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }
}
