<?php

declare(strict_types=1);

namespace Domain\Service\Model;

use Domain\Incident\Model\IncidentCollection;

class Service
{
    private string $id;
    private string $name;
    private string $description;
    private IncidentCollection $incidents;

    public function __construct(string $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->incidents = new IncidentCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIncidents(): IncidentCollection
    {
        return $this->incidents;
    }
}
