<?php

declare(strict_types=1);

class TimelineEvent
{
    private string $id;
    private TimelineEventType $type;
    private string $message;
    private DateTime $occurredAt;
    private bool $raisedBySystem;
    private Incident $incident;
    private User $author;
}