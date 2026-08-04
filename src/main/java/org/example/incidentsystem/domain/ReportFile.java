package org.example.incidentsystem.domain;

import jakarta.persistence.Embeddable;
import java.util.Objects;

@Embeddable
public record ReportFile(String url) {

    public ReportFile { Objects.requireNonNull(url, "url"); }
}
