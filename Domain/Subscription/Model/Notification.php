<?php

declare(strict_types=1);

class Notification
{
    private string $id;
    private NotificationChannel $channel;
    private DateTime $sentAt;
    private Incident $incident;
    private Subscription $subscription;
}
