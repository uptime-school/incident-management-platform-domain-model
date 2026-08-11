using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Enums;

namespace IncidentManagement.Domain.Entities;

public sealed class Notification : Entity
{
    public Guid IncidentId { get; }
    public Guid CommentId { get; }
    public Guid RecipientUserId { get; }
    public NotificationChannel Channel { get; }
    public DateTimeOffset SentAt { get; }

    internal Notification(
        Guid id,
        Guid incidentId,
        Guid commentId,
        Guid recipientUserId,
        NotificationChannel channel,
        DateTimeOffset sentAt) : base(id)
    {
        IncidentId = incidentId;
        CommentId = commentId;
        RecipientUserId = recipientUserId;
        Channel = channel;
        SentAt = sentAt;
    }
}
