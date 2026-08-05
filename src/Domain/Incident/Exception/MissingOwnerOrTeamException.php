<?php

declare(strict_types=1);

namespace App\Domain\Incident\Exception;

use App\Domain\Common\Exception\DomainException;

class MissingOwnerOrTeamException extends DomainException
{
    public function __construct(string $incidentId)
    {
        parent::__construct(sprintf('Cannot investigate incident "%s" without an assigned team or owner.', $incidentId));
    }
}