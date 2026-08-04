package org.example.incidentsystem.domain;

import org.example.incidentsystem.enums.*;
import org.junit.jupiter.api.Test;
import java.time.Instant;
import static org.junit.jupiter.api.Assertions.*;

class IncidentTest {
    private final Instant created = Instant.parse("2026-08-03T10:00:00Z");

    private final User user = new User(
            "Alex Doe",
            new EmailAddress("alex@example.com"),
            new Role(1L, "user", "can use free points")
    );

    private Incident incident() {
        return new Incident(
                "API unavailable",
                "Requests fail",
                Severity.SEV_ONE, user, created
        );
    }

    @Test
    void investigatingRequiresOwnerOrTeam() {
        Incident incident = incident();
        assertThrows(
                IllegalStateException.class,
                () -> incident.changeStatus(IncidentStatus.INVESTIGATING, user, created.plusSeconds(1))
        );
        assertTrue(incident.getTimeline().isEmpty());

        incident.assignResponsibleTeam("Platform", created.plusSeconds(2));
        incident.changeStatus(IncidentStatus.INVESTIGATING, user, created.plusSeconds(3));

        assertEquals(1, incident.getTimeline().size());
    }

    @Test
    void eachActualStatusChangeAddsExactlyOneEvent() {
        Incident incident = incident();
        incident.assignParticipant(user, ParticipantRole.OWNER, user, created);
        incident.changeStatus(IncidentStatus.INVESTIGATING, user, created.plusSeconds(1));
        incident.changeStatus(IncidentStatus.MITIGATING, user, created.plusSeconds(2));
        incident.changeStatus(IncidentStatus.MITIGATING, user, created.plusSeconds(3));

        assertEquals(2, incident.getTimeline().size());
    }

    @Test
    void resolvedAtIsSetOnResolveAndClearedOnReopen() {
        Incident incident = incident();
        Instant resolved = created.plusSeconds(10);
        incident.changeStatus(IncidentStatus.RESOLVED, user, resolved);

        assertEquals(resolved, incident.getResolvedAt());

        incident.changeStatus(IncidentStatus.CLOSED, user, resolved.plusSeconds(1));

        assertEquals(resolved, incident.getResolvedAt());

        incident.changeStatus(IncidentStatus.OPEN, user, resolved.plusSeconds(2));

        assertNull(incident.getResolvedAt());
    }

    @Test
    void postmortemRequiresFinishedIncidentAndIsUnique() {
        Incident incident = incident();
        assertThrows(IllegalStateException.class, () -> incident.createPostmortem("Cause", null));

        incident.changeStatus(IncidentStatus.RESOLVED, user, created.plusSeconds(1));
        incident.createPostmortem("Cause", new ReportFile("https://example.com/report"));

        assertThrows(IllegalStateException.class, () -> incident.createPostmortem("Another", null));
    }
}
