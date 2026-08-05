<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

class MitigatingStatus extends AbstractIncidentStatus
{
    public const string MESSAGE = 'Status changed to Mitigating';

    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function resolve(): IncidentStatus
    {
        return new ResolvedStatus();
    }
}