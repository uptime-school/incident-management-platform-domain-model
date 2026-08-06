<?php

declare(strict_types=1);

namespace App\Domain\Incident\Exception;

use App\Domain\Common\Exception\DomainException;

class InvalidStatusTransitionException extends DomainException
{
    public function __construct(string $action, string $currentState)
    {
        parent::__construct(sprintf('Cannot "%s" an incident in state "%s".', $action, $currentState));
    }
}
