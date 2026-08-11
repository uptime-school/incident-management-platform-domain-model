using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Entities;

public sealed class Comment : Entity
{
    public Guid IncidentId { get; }
    public Guid AuthorId { get; }
    public string Text { get; }
    public DateTimeOffset WrittenAt { get; }

    public Comment(Guid id, Guid incidentId, Guid authorId, string text, DateTimeOffset writtenAt) : base(id)
    {
        if (string.IsNullOrWhiteSpace(text))
            throw new ArgumentException("Comment text cannot be empty.", nameof(text));

        IncidentId = incidentId;
        AuthorId = authorId;
        Text = text;
        WrittenAt = writtenAt;
    }
}
