<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EmailAddress
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        private readonly string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
