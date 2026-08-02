using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.Comments.Errors;
using IncidentManagement.Domain.Comments.ValueObjects;
using IncidentManagement.Domain.Users.ValueObjects;

namespace IncidentManagement.Domain.Comments;

public sealed class Comment
{
    private Comment()
    {
    }

    public CommentId Id { get; private set; }

    public string Text { get; private set; } = string.Empty;

    public UserId WrittenByUserId { get; private set; }

    public DateTimeOffset WrittenAt { get; private set; }

    public static Result<Comment> Create(CommentId id, string text, UserId writtenByUserId, DateTimeOffset writtenAt)
    {
        if (string.IsNullOrWhiteSpace(text))
        {
            return Result<Comment>.Failure<Comment>(CommentErrors.TextRequired);
        }

        var comment = new Comment
        {
            Id = id,
            Text = text.Trim(),
            WrittenByUserId = writtenByUserId,
            WrittenAt = writtenAt
        };

        return Result<Comment>.Success(comment);
    }
}
