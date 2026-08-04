package org.example.incidentsystem.domain;

import jakarta.persistence.Embeddable;
import java.util.Locale;
import java.util.regex.Pattern;

@Embeddable
public record EmailAddress(String value) {

    private static final Pattern FORMAT = Pattern.compile("^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$");

    public EmailAddress {
        if (value == null || !FORMAT.matcher(value).matches()) throw new IllegalArgumentException("Invalid email address");
        value = value.toLowerCase(Locale.ROOT);
    }
}
