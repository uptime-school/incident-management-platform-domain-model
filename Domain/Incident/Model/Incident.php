<?php

declare(strict_types=1);

class Incident
{
    private string $id;
    private string $title;
    private string $description;
    private IncidentStatus $status;
    private Severity $severity;
    private DateTime $declaredAt;
    private DateTime $resolvedAt;
}