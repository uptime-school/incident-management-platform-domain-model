<?php

declare(strict_types=1);

namespace App\Domain\Incident\Exception;

use App\Domain\Common\Exception\DomainException;

class PostmortemReportAlreadyExistsException extends DomainException
{
    public function __construct(string $incidentId)
    {
        parent::__construct(sprintf('Incident "%s" already has a postmortem report.', $incidentId));
    }
}
