<?php

declare(strict_types=1);

class Comment
{
    private string $id;
    private string $text;
    private DateTime $writtenAt;
    private Incident $incident;
    private User $author;
}