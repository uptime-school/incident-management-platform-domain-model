namespace IncidentManagement.Domain.Comments.ValueObjects;

public readonly record struct CommentId(string Value)
{
    public static CommentId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
