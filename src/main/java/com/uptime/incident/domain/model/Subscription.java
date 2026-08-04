package com.uptime.incident.domain.model;

import com.uptime.incident.domain.model.enums.NotificationChannel;

import java.time.Instant;

public class Subscription {
    private NotificationChannel notificationChannel;
    private Instant subscribedAt;

    public NotificationChannel getNotificationChannel() {
        return notificationChannel;
    }

    public void setNotificationChannel(NotificationChannel notificationChannel) {
        this.notificationChannel = notificationChannel;
    }

    public Instant getSubscribedAt() {
        return subscribedAt;
    }

    public void setSubscribedAt(Instant subscribedAt) {
        this.subscribedAt = subscribedAt;
    }
}
