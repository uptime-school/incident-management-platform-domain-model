<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Model;

use DateTimeImmutable;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Notification extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(enumType: NotificationChannel::class)]
        private readonly NotificationChannel $channel,
        #[ORM\Column]
        private readonly DateTimeImmutable $sentAt,
        #[ORM\Column(length: 36)]
        private readonly string $incidentId,
        #[ORM\Column(length: 36)]
        private readonly string $subscriptionId
    ) {
        parent::__construct(new Id($id));
    }

    public function getChannel(): NotificationChannel
    {
        return $this->channel;
    }

    public function getSentAt(): DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function getIncidentId(): string
    {
        return $this->incidentId;
    }

    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }
}
