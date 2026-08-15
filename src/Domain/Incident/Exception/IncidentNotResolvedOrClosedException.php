<?php

declare(strict_types=1);

namespace App\Domain\Incident\Exception;

use App\Domain\Common\Exception\DomainException;

class IncidentNotResolvedOrClosedException extends DomainException
{
    public function __construct(string $incidentId)
    {
        parent::__construct(sprintf('Cannot attach a postmortem report to incident "%s" unless it is Resolved or Closed.', $incidentId));
    }
}
