using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;

namespace IncidentManagement.Domain.Entities;

public sealed class Subscription : Entity
{
    public Guid IncidentId { get; }
    public Guid UserId { get; }
    public NotificationChannel Channel { get; }
    public DateTimeOffset SubscribedAt { get; }

    public Subscription(
        Guid id,
        Guid incidentId,
        Guid userId,
        NotificationChannel channel,
        DateTimeOffset subscribedAt) : base(id)
    {
        IncidentId = incidentId;
        UserId = userId;
        Channel = channel;
        SubscribedAt = subscribedAt;
    }
}
