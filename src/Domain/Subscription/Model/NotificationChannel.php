<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Model;

enum NotificationChannel: string
{
    case Email = 'Email';
    case Sms = 'Sms';
    case Chat = 'Chat';
    case InApp = 'InApp';
}