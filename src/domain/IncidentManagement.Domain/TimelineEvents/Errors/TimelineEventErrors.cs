using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.TimelineEvents.Errors;

public static class TimelineEventErrors
{
    public static readonly Error MessageRequired = Error.Validation(
        "TimelineEvents.MessageRequired",
        "Timeline event message is required.");
}
