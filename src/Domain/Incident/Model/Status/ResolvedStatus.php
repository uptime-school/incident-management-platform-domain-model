<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

class ResolvedStatus extends AbstractIncidentStatus
{
    public const string MESSAGE = 'Status changed to Resolved';

    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function close(): IncidentStatus
    {
        return new ClosedStatus();
    }
}