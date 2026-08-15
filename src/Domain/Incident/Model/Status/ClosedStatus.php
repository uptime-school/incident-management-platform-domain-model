<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

class ClosedStatus extends AbstractIncidentStatus
{
    public const string MESSAGE = 'Status changed to Closed';
}