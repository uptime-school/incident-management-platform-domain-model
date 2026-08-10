package org.example.incidentsystem.domain;

import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Getter;
import lombok.NoArgsConstructor;
import org.example.incidentsystem.enums.IncidentStatus;
import org.example.incidentsystem.enums.TimelineEventType;
import org.hibernate.annotations.CreationTimestamp;
import org.springframework.data.annotation.CreatedBy;

import java.time.Instant;

@Entity
@Getter
@NoArgsConstructor(access = AccessLevel.PROTECTED)
public class TimelineEvent {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String message;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private TimelineEventType type;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Incident incident;

    @CreatedBy
    @ManyToOne(fetch = FetchType.LAZY)
    private User createdBy;

    @CreationTimestamp
    @Column(nullable = false, updatable = false)
    private Instant createdAt;

    static TimelineEvent statusChanged(Incident incident, IncidentStatus from, IncidentStatus to, User by, Instant at) {
        TimelineEvent event = new TimelineEvent();
        event.incident = incident;
        event.type = TimelineEventType.STATUS_CHANGED;
        event.message = from + " -> " + to;
        event.createdBy = by;
        event.createdAt = at;
        return event;
    }
}
