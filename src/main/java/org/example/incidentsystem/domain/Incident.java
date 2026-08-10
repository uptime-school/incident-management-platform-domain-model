package org.example.incidentsystem.domain;

import jakarta.persistence.*;
import lombok.Getter;
import lombok.NoArgsConstructor;
import org.example.incidentsystem.enums.IncidentStatus;
import org.example.incidentsystem.enums.ParticipantRole;
import org.example.incidentsystem.enums.Severity;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;
import org.springframework.data.annotation.CreatedBy;

import java.time.Instant;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Objects;

@Entity
@Getter
@NoArgsConstructor(access = lombok.AccessLevel.PROTECTED)
public class Incident {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String title;

    @Column(nullable = false, length = 4000)
    private String description;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private Severity severity;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private IncidentStatus status;

    @CreatedBy
    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private User createdBy;

    @CreationTimestamp
    @Column(nullable = false, updatable = false)
    private Instant createdAt;

    @UpdateTimestamp
    private Instant updatedAt;

    private Instant resolvedAt;

    private String responsibleTeam;

    @OneToMany(mappedBy = "incident", cascade = CascadeType.ALL, orphanRemoval = true)
    private final List<IncidentParticipant> participants = new ArrayList<>();

    @OneToMany(mappedBy = "incident", cascade = CascadeType.ALL, orphanRemoval = true)
    private final List<Comment> comments = new ArrayList<>();

    @OneToMany(mappedBy = "incident", cascade = CascadeType.ALL, orphanRemoval = true)
    @OrderBy("createdAt ASC")
    private final List<TimelineEvent> timeline = new ArrayList<>();

    @OneToOne(mappedBy = "incident", cascade = CascadeType.ALL, orphanRemoval = true)
    private PostmortemReport postmortem;

    public Incident(String title, String description, Severity severity, User createdBy, Instant now) {
        this.title = requireText(title, "title");
        this.description = requireText(description, "description");
        this.severity = Objects.requireNonNull(severity, "severity");
        this.createdBy = Objects.requireNonNull(createdBy, "createdBy");
        this.status = IncidentStatus.OPEN;
        this.createdAt = Objects.requireNonNull(now, "now");
        this.updatedAt = now;
    }

    public void assignParticipant(User user, ParticipantRole role, User assignedBy, Instant now) {
        participants.add(new IncidentParticipant(this, user, role, assignedBy, now));
    }

    public void assignResponsibleTeam(String team, Instant now) {
        responsibleTeam = requireText(team, "responsibleTeam");
        updatedAt = Objects.requireNonNull(now, "now");
    }

    public void changeStatus(IncidentStatus newStatus, User changedBy, Instant now) {
        Objects.requireNonNull(newStatus, "newStatus");
        Objects.requireNonNull(now, "now");
        if (newStatus == status) return;
        if (newStatus == IncidentStatus.INVESTIGATING && !hasOwner() && responsibleTeam == null) {
            throw new IllegalStateException("An incident needs an owner or responsible team before investigation");
        }
        IncidentStatus previous = status;
        status = newStatus;
        if (newStatus == IncidentStatus.RESOLVED) resolvedAt = now;
        if (previous.isFinished() && !newStatus.isFinished()) resolvedAt = null;
        updatedAt = now;
        timeline.add(TimelineEvent.statusChanged(this, previous, newStatus, changedBy, now));
    }

    public PostmortemReport createPostmortem(String summary, ReportFile file) {
        if (status != IncidentStatus.RESOLVED && status != IncidentStatus.CLOSED) {
            throw new IllegalStateException("A postmortem can only be created for a resolved or closed incident");
        }
        if (postmortem != null) throw new IllegalStateException("This incident already has a postmortem");
        postmortem = new PostmortemReport(this, summary, file);
        return postmortem;
    }

    public List<IncidentParticipant> getParticipants() { return Collections.unmodifiableList(participants); }

    public List<Comment> getComments() { return Collections.unmodifiableList(comments); }

    public List<TimelineEvent> getTimeline() { return Collections.unmodifiableList(timeline); }

    private boolean hasOwner() {
        return participants.stream().anyMatch(p -> p.getRole() == ParticipantRole.OWNER);
    }

    private static String requireText(String value, String name) {
        if (value == null || value.isBlank()) throw new IllegalArgumentException(name + " must not be blank");
        return value;
    }
}
