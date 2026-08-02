namespace IncidentManagement.Domain.Incidents;

public enum IncidentStatus
{
    Open = 1,
    Investigating = 2,
    Mitigating = 3,
    Resolved = 4,
    Closed = 5,
    Cancelled = 6
}
