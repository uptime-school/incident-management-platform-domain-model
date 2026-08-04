<?php

declare(strict_types=1);

namespace Domain\Incident\Model\Status;

class OpenStatus extends AbstractIncidentStatus
{
    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function cancel(): IncidentStatus
    {
        return new CancelledStatus();
    }
}