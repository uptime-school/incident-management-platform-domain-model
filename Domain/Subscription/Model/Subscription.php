<?php

declare(strict_types=1);

class Subscription
{
    private string $id;
    private NotificationChannel $channel;
    private DateTime $subscribedAt;
    private User $user;
}
