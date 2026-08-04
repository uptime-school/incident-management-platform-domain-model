<?php

declare(strict_types=1);

namespace Domain\Action\Model;

use DateTime;
use Domain\PostmortemReport\Model\PostmortemReport;
use Domain\User\Model\User;

class Action
{
    private string $id;
    private string $description;
    private DateTime $deadline;
    private ActionStatus $status;
    private User $assignedTo;
    private PostmortemReport $postmortemReport;

    public function __construct(
        string $id,
        string $description,
        DateTime $deadline,
        ActionStatus $status,
        User $assignedTo,
        PostmortemReport $postmortemReport
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->status = $status;
        $this->assignedTo = $assignedTo;
        $this->postmortemReport = $postmortemReport;
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getAssignedTo(): User
    {
        return $this->assignedTo;
    }

    public function getPostmortemReport(): PostmortemReport
    {
        return $this->postmortemReport;
    }
}
