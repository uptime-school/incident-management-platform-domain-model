<?php

declare(strict_types=1);

namespace App\Domain\TimelineEvent\Model;

use DateTimeImmutable;
use App\Domain\Incident\Model\Incident;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class TimelineEvent extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(enumType: TimelineEventType::class)]
        private readonly TimelineEventType $type,
        #[ORM\Column(type: 'text')]
        private readonly string $message,
        #[ORM\Column]
        private readonly DateTimeImmutable $createdAt,
        #[ORM\Column]
        private readonly bool $raisedBySystem,
        #[ORM\ManyToOne(targetEntity: Incident::class, inversedBy: 'timelineEvents')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Incident $incident,
        #[ORM\Column(length: 36, nullable: true)]
        private readonly ?string $authorId = null
    ) {
        parent::__construct(new Id($id));
    }

    public static function create(
        TimelineEventType $type,
        string $message,
        DateTimeImmutable $occurredAt,
        bool $raisedBySystem,
        Incident $incident,
        ?string $authorId = null
    ): self {
        return new self(
            Uuid::v4()->toRfc4122(),
            $type,
            $message,
            $occurredAt,
            $raisedBySystem,
            $incident,
            $authorId
        );
    }

    public function getType(): TimelineEventType
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isRaisedBySystem(): bool
    {
        return $this->raisedBySystem;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }
}