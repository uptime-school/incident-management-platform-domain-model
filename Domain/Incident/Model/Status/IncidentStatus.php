<?php

declare(strict_types=1);

interface IncidentStatus
{
    public function investigate(): IncidentStatus;

    public function mitigate(): IncidentStatus;

    public function resolve(): IncidentStatus;

    public function close(): IncidentStatus;

    public function cancel(): IncidentStatus;
}