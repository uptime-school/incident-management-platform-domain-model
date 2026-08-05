<?php

declare(strict_types=1);

namespace App\Domain\TimelineEvent\Model;

use DateTime;
use App\Domain\Incident\Model\Incident;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use App\Domain\User\Model\User;
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
        private readonly DateTime $occurredAt,
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
        DateTime $occurredAt,
        bool $raisedBySystem,
        Incident $incident,
        ?User $author = null
    ): self {
        return new self(
            Uuid::v4()->toRfc4122(),
            $type,
            $message,
            $occurredAt,
            $raisedBySystem,
            $incident,
            $author?->getId()
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

    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }
}
