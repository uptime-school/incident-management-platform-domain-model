<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model;

use DateTimeImmutable;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Comment extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(type: 'text')]
        private readonly string $text,
        #[ORM\Column]
        private readonly DateTimeImmutable $writtenAt,
        #[ORM\Column(length: 36)]
        private readonly string $incidentId,
        #[ORM\Column(length: 36)]
        private readonly string $authorId
    ) {
        parent::__construct(new Id($id));
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getWrittenAt(): DateTimeImmutable
    {
        return $this->writtenAt;
    }

    public function getIncidentId(): string
    {
        return $this->incidentId;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }
}
