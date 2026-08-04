package org.example.incidentsystem.domain;

import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Getter;
import lombok.NoArgsConstructor;
import org.hibernate.annotations.CreationTimestamp;
import org.springframework.data.annotation.CreatedBy;

import java.time.Instant;
import java.util.Objects;

@Entity
@Getter
@NoArgsConstructor(access = AccessLevel.PROTECTED)
public class PostmortemReport {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @OneToOne(fetch = FetchType.LAZY, optional = false)
    @JoinColumn(nullable = false, unique = true)
    private Incident incident;

    @Column(nullable = false, length = 10000)
    private String summary;

    @Embedded
    private ReportFile file;

    @CreatedBy
    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private User author;

    @CreationTimestamp
    @Column(nullable = false, updatable = false)
    private Instant createdAt;

    PostmortemReport(Incident incident, String summary, ReportFile file) {
        this.incident = Objects.requireNonNull(incident);
        if (summary == null || summary.isBlank()) throw new IllegalArgumentException("summary must not be blank");
        this.summary = summary;
        this.file = file;
    }
}
