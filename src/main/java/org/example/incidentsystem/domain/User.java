package org.example.incidentsystem.domain;

import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Getter;
import lombok.NoArgsConstructor;
import org.hibernate.annotations.CreationTimestamp;
import org.springframework.data.annotation.CreatedDate;

import java.time.Instant;
import java.util.HashSet;
import java.util.Objects;
import java.util.Set;

@Entity
@Table(name = "app_user")
@Getter
@NoArgsConstructor(access = AccessLevel.PROTECTED)
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String fullName;

    @Embedded
    @AttributeOverride(
            name = "value", column = @Column(
                    name = "email", nullable = false, unique = true
    ))
    private EmailAddress email;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    private Team team;

    @ManyToMany
    private final Set<Role> roles = new HashSet<>();

    @CreationTimestamp
    @Column(nullable = false, updatable = false)
    private Instant createdAt;

    public User(String fullName, EmailAddress email, Role role) {
        this.fullName = Objects.requireNonNull(fullName);
        this.email = Objects.requireNonNull(email);
        addRole(role);
    }

    public void addRole(Role role) {
        if (Objects.nonNull(role)) this.roles.add(role);
    }
}
