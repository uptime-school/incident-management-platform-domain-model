package com.uptime.incident.domain.model;

import org.springframework.http.MediaType;

import java.net.URI;

public class ReportFile {
    private URI location;
    private MediaType contentType;

    public URI getLocation() {
        return location;
    }

    public void setLocation(URI location) {
        this.location = location;
    }

    public MediaType getContentType() {
        return contentType;
    }

    public void setContentType(MediaType contentType) {
        this.contentType = contentType;
    }
}
