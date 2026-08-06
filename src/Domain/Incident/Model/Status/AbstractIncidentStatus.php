<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model\Status;

use App\Domain\Incident\Exception\InvalidStatusTransitionException;

abstract class AbstractIncidentStatus implements IncidentStatus
{
    public function investigate(): IncidentStatus
    {
        throw $this->invalidTransition('investigate');
    }

    public function mitigate(): IncidentStatus
    {
        throw $this->invalidTransition('mitigate');
    }

    public function resolve(): IncidentStatus
    {
        throw $this->invalidTransition('resolve');
    }

    public function close(): IncidentStatus
    {
        throw $this->invalidTransition('close');
    }

    public function cancel(): IncidentStatus
    {
        throw $this->invalidTransition('cancel');
    }

    public function getTransitionMessage(): string
    {
        return static::MESSAGE;
    }

    private function invalidTransition(string $action): InvalidStatusTransitionException
    {
        return new InvalidStatusTransitionException($action, static::class);
    }
}