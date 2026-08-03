<?php

declare(strict_types=1);

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

    private function invalidTransition(string $action): \LogicException
    {
        return new \LogicException(sprintf('Cannot "%s" an incident in state "%s".', $action, static::class));
    }
}