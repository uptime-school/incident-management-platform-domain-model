<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

class OpenStatus extends AbstractIncidentStatus
{
    public const string MESSAGE = 'Status changed to Open';

    public function investigate(): IncidentStatus
    {
        return new InvestigatingStatus();
    }

    public function cancel(): IncidentStatus
    {
        return new CancelledStatus();
    }
}