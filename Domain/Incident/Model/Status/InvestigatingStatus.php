<?php

declare(strict_types=1);

class InvestigatingStatus extends AbstractIncidentStatus
{
    public function mitigate(): IncidentStatus
    {
        return new MitigatingStatus();
    }

    public function cancel(): IncidentStatus
    {
        return new CancelledStatus();
    }
}