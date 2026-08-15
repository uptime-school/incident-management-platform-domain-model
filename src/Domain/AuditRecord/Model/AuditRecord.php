<?php

declare(strict_types=1);

namespace App\Domain\AuditRecord\Model;

use DateTimeImmutable;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AuditRecord extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(enumType: AuditAction::class)]
        private readonly AuditAction $action,
        #[ORM\Column(type: 'text')]
        private readonly string $previousValue,
        #[ORM\Column(type: 'text')]
        private readonly string $newValue,
        #[ORM\Column]
        private readonly DateTimeImmutable $createdAt,
        #[ORM\Column(length: 36)]
        private readonly string $performedById,
        #[ORM\Column(length: 36)]
        private readonly string $incidentId
    ) {
        parent::__construct(new Id($id));
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPerformedById(): string
    {
        return $this->performedById;
    }

    public function getIncidentId(): string
    {
        return $this->incidentId;
    }
}