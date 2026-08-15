<?php

declare(strict_types=1);

namespace App\Domain\PostmortemReport\Model;

use DateTimeImmutable;
use App\Domain\Incident\Model\Incident;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class PostmortemReport extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(type: 'text')]
        private string $summary,
        #[ORM\Column]
        private readonly DateTimeImmutable $createdAt,
        #[ORM\Column(length: 36)]
        private readonly string $fileId,
        #[ORM\OneToOne(targetEntity: Incident::class, inversedBy: 'postmortemReport')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Incident $incident
    ) {
        parent::__construct(new Id($id));
    }

    public static function create(
        string $summary,
        DateTimeImmutable $generatedAt,
        string $fileId,
        Incident $incident
    ): self {
        return new self(
            Uuid::v4()->toRfc4122(),
            $summary,
            $generatedAt,
            $fileId,
            $incident
        );
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }
}