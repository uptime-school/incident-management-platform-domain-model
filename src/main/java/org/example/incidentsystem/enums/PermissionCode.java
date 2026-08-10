package org.example.incidentsystem.enums;

import lombok.AllArgsConstructor;
import lombok.Getter;

@Getter
@AllArgsConstructor
public enum PermissionCode {
    CREATE("001"),
    READ("002"),
    UPDATE("003"),
    DELETE("004");

    private final String code;

}
