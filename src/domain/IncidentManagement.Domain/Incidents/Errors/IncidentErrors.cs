using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.Incidents.Errors;

public static class IncidentErrors
{
    public static readonly Error TitleRequired = Error.Validation(
        "Incidents.TitleRequired",
        "Incident title is required.");

    public static readonly Error DescriptionRequired = Error.Validation(
        "Incidents.DescriptionRequired",
        "Incident description is required.");

    public static readonly Error OwnerRequiredForInvestigation = Error.Validation(
        "Incidents.OwnerRequiredForInvestigation",
        "An incident cannot enter Investigating without an owner.");

    public static readonly Error PostmortemRequiresResolvedOrClosedIncident = Error.Validation(
        "Incidents.PostmortemRequiresResolvedOrClosedIncident",
        "A postmortem can be created only after the incident is resolved or closed.");

    public static readonly Error PostmortemAlreadyExists = Error.Conflict(
        "Incidents.PostmortemAlreadyExists",
        "An incident can have at most one postmortem report.");
}
