<?php

declare(strict_types=1);

class MitigatingStatus extends AbstractIncidentStatus
{
    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function resolve(): IncidentStatus
    {
        return new ResolvedStatus();
    }
}