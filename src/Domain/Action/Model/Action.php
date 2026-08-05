<?php

declare(strict_types=1);

namespace App\Domain\Action\Model;

use DateTime;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Action extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(type: 'text')]
        private readonly string $description,
        #[ORM\Column]
        private readonly DateTime $deadline,
        #[ORM\Column(enumType: ActionStatus::class)]
        private ActionStatus $status,
        #[ORM\Column(length: 36)]
        private readonly string $assignedToId,
        #[ORM\Column(length: 36)]
        private readonly string $postmortemReportId
    ) {
        parent::__construct(new Id($id));
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDeadline(): DateTime
    {
        return $this->deadline;
    }

    public function getStatus(): ActionStatus
    {
        return $this->status;
    }

    public function getAssignedToId(): string
    {
        return $this->assignedToId;
    }

    public function getPostmortemReportId(): string
    {
        return $this->postmortemReportId;
    }
}
