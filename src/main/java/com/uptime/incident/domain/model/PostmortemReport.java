package com.uptime.incident.domain.model;

import java.time.Instant;

public class PostmortemReport {
    private String summary;
    private Instant generatedAt;
    private ReportFile reportFile;

    public String getSummary() {
        return summary;
    }

    public void setSummary(String summary) {
        this.summary = summary;
    }

    public Instant getGeneratedAt() {
        return generatedAt;
    }

    public void setGeneratedAt(Instant generatedAt) {
        this.generatedAt = generatedAt;
    }

    public ReportFile getReportFile() {
        return reportFile;
    }

    public void setReportFile(ReportFile reportFile) {
        this.reportFile = reportFile;
    }
}
