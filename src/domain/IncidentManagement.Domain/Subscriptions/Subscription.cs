using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Subscriptions.ValueObjects;
using IncidentManagement.Domain.Users.ValueObjects;

namespace IncidentManagement.Domain.Subscriptions;

public sealed class Subscription
{
    private Subscription()
    {
    }

    public SubscriptionId Id { get; private set; }

    public UserId UserId { get; private set; }

    public NotificationChannel Channel { get; private set; }

    public DateTimeOffset SubscribedAt { get; private set; }

    public static Result<Subscription> Create(
        SubscriptionId id,
        UserId userId,
        NotificationChannel channel,
        DateTimeOffset subscribedAt)
    {
        var subscription = new Subscription
        {
            Id = id,
            UserId = userId,
            Channel = channel,
            SubscribedAt = subscribedAt
        };

        return Result<Subscription>.Success(subscription);
    }
}
