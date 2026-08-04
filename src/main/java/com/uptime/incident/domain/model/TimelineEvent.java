package com.uptime.incident.domain.model;

import com.uptime.incident.domain.model.enums.TimelineEventType;

import java.time.Instant;

public class TimelineEvent {
    private TimelineEventType timelineEventType;
    private String message;
    private Instant occurredAt;
    private Boolean raisedBySystem;

    public TimelineEventType getTimelineEventType() {
        return timelineEventType;
    }

    public void setTimelineEventType(TimelineEventType timelineEventType) {
        this.timelineEventType = timelineEventType;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Instant getOccurredAt() {
        return occurredAt;
    }

    public void setOccurredAt(Instant occurredAt) {
        this.occurredAt = occurredAt;
    }

    public Boolean getRaisedBySystem() {
        return raisedBySystem;
    }

    public void setRaisedBySystem(Boolean raisedBySystem) {
        this.raisedBySystem = raisedBySystem;
    }
}
