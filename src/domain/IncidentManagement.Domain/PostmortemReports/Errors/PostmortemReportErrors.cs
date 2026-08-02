using IncidentManagement.Domain.Common;

namespace IncidentManagement.Domain.PostmortemReports.Errors;

public static class PostmortemReportErrors
{
    public static readonly Error SummaryRequired = Error.Validation(
        "PostmortemReports.SummaryRequired",
        "Postmortem report summary is required.");
}
