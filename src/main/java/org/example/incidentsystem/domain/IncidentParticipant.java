package org.example.incidentsystem.domain;

import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Getter;
import lombok.NoArgsConstructor;
import org.example.incidentsystem.enums.ParticipantRole;
import org.hibernate.annotations.CreationTimestamp;
import org.springframework.data.annotation.CreatedBy;

import java.time.Instant;
import java.util.Objects;

@Entity
@Getter
@NoArgsConstructor(access = AccessLevel.PROTECTED)
public class IncidentParticipant {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Incident incident;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private User user;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private ParticipantRole role;

    @CreatedBy
    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private User assignedBy;

    @CreationTimestamp
    @Column(nullable = false, updatable = false)
    private Instant assignedAt;

    IncidentParticipant(Incident incident, User user, ParticipantRole role, User assignedBy, Instant assignedAt) {
        this.incident = Objects.requireNonNull(incident);
        this.user = Objects.requireNonNull(user);
        this.role = Objects.requireNonNull(role);
    }
}
