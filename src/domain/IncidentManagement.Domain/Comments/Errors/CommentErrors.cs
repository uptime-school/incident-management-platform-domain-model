using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Comments.Errors;

public static class CommentErrors
{
    public static readonly Error TextRequired = Error.Validation(
        "Comments.TextRequired",
        "Comment text is required.");
}
