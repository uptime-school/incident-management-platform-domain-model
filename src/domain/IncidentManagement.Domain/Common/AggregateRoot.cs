namespace IncidentManagement.Domain.Common;

public abstract class AggregateRoot<TId>
{
    public TId Id { get; protected init; } = default!;
    
    //there would be more code here for domain events, but it's not part of the task for now
}
