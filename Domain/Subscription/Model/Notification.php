<?php

declare(strict_types=1);

namespace Domain\Subscription\Model;

use DateTime;
use Domain\Incident\Model\Incident;

class Notification
{
    private string $id;
    private NotificationChannel $channel;
    private DateTime $sentAt;
    private Incident $incident;
    private Subscription $subscription;

    public function __construct(string $id, NotificationChannel $channel, DateTime $sentAt, Incident $incident, Subscription $subscription)
    {
        $this->id = $id;
        $this->channel = $channel;
        $this->sentAt = $sentAt;
        $this->incident = $incident;
        $this->subscription = $subscription;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getChannel(): NotificationChannel
    {
        return $this->channel;
    }

    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
