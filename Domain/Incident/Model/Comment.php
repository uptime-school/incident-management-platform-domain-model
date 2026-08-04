<?php

declare(strict_types=1);

namespace Domain\Incident\Model;

use DateTime;
use Domain\User\Model\User;

class Comment
{
    private string $id;
    private string $text;
    private DateTime $writtenAt;
    private Incident $incident;
    private User $author;

    public function __construct(string $id, string $text, DateTime $writtenAt, Incident $incident, User $author)
    {
        $this->id = $id;
        $this->text = $text;
        $this->writtenAt = $writtenAt;
        $this->incident = $incident;
        $this->author = $author;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getWrittenAt(): DateTime
    {
        return $this->writtenAt;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
}