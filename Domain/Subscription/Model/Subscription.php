<?php

declare(strict_types=1);

namespace Domain\Subscription\Model;

use DateTime;
use Domain\Incident\Model\Incident;
use Domain\User\Model\User;

class Subscription
{
    private string $id;
    private NotificationChannel $channel;
    private DateTime $subscribedAt;
    private User $user;
    private Incident $incident;
    private NotificationCollection $notifications;

    public function __construct(string $id, NotificationChannel $channel, DateTime $subscribedAt, User $user, Incident $incident)
    {
        $this->id = $id;
        $this->channel = $channel;
        $this->subscribedAt = $subscribedAt;
        $this->user = $user;
        $this->incident = $incident;
        $this->notifications = new NotificationCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getChannel(): NotificationChannel
    {
        return $this->channel;
    }

    public function getSubscribedAt(): DateTime
    {
        return $this->subscribedAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getNotifications(): NotificationCollection
    {
        return $this->notifications;
    }
}
