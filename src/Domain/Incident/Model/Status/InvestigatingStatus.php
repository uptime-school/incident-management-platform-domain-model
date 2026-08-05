<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

class InvestigatingStatus extends AbstractIncidentStatus
{
    public const string MESSAGE = 'Status changed to Investigating';

    public function mitigate(): IncidentStatus
    {
        return new MitigatingStatus();
    }

    public function cancel(): IncidentStatus
    {
        return new CancelledStatus();
    }
}