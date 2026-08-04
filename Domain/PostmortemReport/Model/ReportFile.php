<?php

declare(strict_types=1);

namespace Domain\PostmortemReport\Model;

class ReportFile
{
    private string $id;
    private string $locationUrl;
    private string $contentType;
    private ?PostmortemReport $postmortemReport = null;

    public function __construct(string $id, string $locationUrl, string $contentType)
    {
        $this->id = $id;
        $this->locationUrl = $locationUrl;
        $this->contentType = $contentType;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLocationUrl(): string
    {
        return $this->locationUrl;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getPostmortemReport(): ?PostmortemReport
    {
        return $this->postmortemReport;
    }

    public function setPostmortemReport(PostmortemReport $postmortemReport): void
    {
        $this->postmortemReport = $postmortemReport;
    }
}