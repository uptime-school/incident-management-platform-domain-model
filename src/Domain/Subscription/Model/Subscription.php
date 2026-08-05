<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Model;

use DateTime;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Subscription extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column(enumType: NotificationChannel::class)]
        private readonly NotificationChannel $channel,
        #[ORM\Column]
        private readonly DateTime $subscribedAt,
        #[ORM\Column(length: 36)]
        private readonly string $userId,
        #[ORM\Column(length: 36)]
        private readonly string $incidentId
    ) {
        parent::__construct(new Id($id));
    }

    public function getChannel(): NotificationChannel
    {
        return $this->channel;
    }

    public function getSubscribedAt(): DateTime
    {
        return $this->subscribedAt;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getIncidentId(): string
    {
        return $this->incidentId;
    }
}
