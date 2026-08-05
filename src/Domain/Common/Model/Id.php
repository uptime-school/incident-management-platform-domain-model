<?php

declare(strict_types=1);

namespace App\Domain\Common\Model;

final readonly class Id
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
