<?php

declare(strict_types=1);

namespace Domain\Incident\Model\Status;

class ResolvedStatus extends AbstractIncidentStatus
{
    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function close(): IncidentStatus
    {
        return new ClosedStatus();
    }
}