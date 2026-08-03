<?php

declare(strict_types=1);

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