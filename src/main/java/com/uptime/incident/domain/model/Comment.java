package com.uptime.incident.domain.model;

import java.time.Instant;

public class Comment {
    private String text;
    private Instant writtenAt;

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public Instant getWrittenAt() {
        return writtenAt;
    }

    public void setWrittenAt(Instant writtenAt) {
        this.writtenAt = writtenAt;
    }
}
