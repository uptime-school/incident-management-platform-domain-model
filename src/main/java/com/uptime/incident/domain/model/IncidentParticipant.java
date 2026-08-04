package com.uptime.incident.domain.model;

import com.uptime.incident.domain.model.enums.ParticipantRole;

import java.time.Instant;

public class IncidentParticipant {
    private ParticipantRole participantRole;
    private Instant assignedAt;
    private User assignedBy;

    public ParticipantRole getParticipantRole() {
        return participantRole;
    }

    public void setParticipantRole(ParticipantRole participantRole) {
        this.participantRole = participantRole;
    }

    public Instant getAssignedAt() {
        return assignedAt;
    }

    public void setAssignedAt(Instant assignedAt) {
        this.assignedAt = assignedAt;
    }

    public User getAssignedBy() {
        return assignedBy;
    }

    public void setAssignedBy(User assignedBy) {
        this.assignedBy = assignedBy;
    }
}
