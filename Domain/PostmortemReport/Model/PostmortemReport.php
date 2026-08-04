<?php

declare(strict_types=1);

namespace Domain\PostmortemReport\Model;

use DateTime;
use Domain\Action\Model\ActionCollection;
use Domain\Incident\Model\Incident;

class PostmortemReport
{
    private string $id;
    private string $summary;
    private DateTime $generatedAt;
    private ReportFile $file;
    private Incident $incident;
    private ActionCollection $actions;

    public function __construct(string $id, string $summary, DateTime $generatedAt, ReportFile $file, Incident $incident)
    {
        $this->id = $id;
        $this->summary = $summary;
        $this->generatedAt = $generatedAt;
        $this->file = $file;
        $this->incident = $incident;
        $this->actions = new ActionCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getGeneratedAt(): DateTime
    {
        return $this->generatedAt;
    }

    public function getFile(): ReportFile
    {
        return $this->file;
    }

    public function getIncident(): Incident
    {
        return $this->incident;
    }

    public function getActions(): ActionCollection
    {
        return $this->actions;
    }
}