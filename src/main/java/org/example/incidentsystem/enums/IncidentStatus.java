package org.example.incidentsystem.enums;

import lombok.AllArgsConstructor;
import lombok.Getter;

@Getter
@AllArgsConstructor
public enum IncidentStatus {

    OPEN("open"),
    INVESTIGATING("investigating"),
    MITIGATING("mitigating"),
    RESOLVED("resolved"),
    CANCELLED("cancelled"),
    CLOSED("closed");

    private final String title;

    public boolean isFinished() {
        return this == RESOLVED || this == CLOSED;
    }

}
