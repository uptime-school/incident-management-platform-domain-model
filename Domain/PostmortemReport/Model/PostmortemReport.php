<?php

declare(strict_types=1);

class PostmortemReport
{
    private string $id;
    private string $summary;
    private DateTime $generatedAt;
    private ReportFile $file;
    private Incident $incident;
}