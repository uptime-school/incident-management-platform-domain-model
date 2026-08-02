using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.PostmortemReports.Errors;
using IncidentManagement.Domain.PostmortemReports.ValueObjects;

namespace IncidentManagement.Domain.PostmortemReports;

public sealed class PostmortemReport
{
    private PostmortemReport()
    {
    }

    public PostmortemReportId Id { get; private set; }

    public string Summary { get; private set; } = string.Empty;

    public DateTimeOffset GeneratedAt { get; private set; }

    public static Result<PostmortemReport> Create(PostmortemReportId id, string summary, DateTimeOffset generatedAt)
    {
        if (string.IsNullOrWhiteSpace(summary))
        {
            return Result<PostmortemReport>.Failure<PostmortemReport>(PostmortemReportErrors.SummaryRequired);
        }

        var report = new PostmortemReport
        {
            Id = id,
            Summary = summary.Trim(),
            GeneratedAt = generatedAt
        };

        return Result<PostmortemReport>.Success(report);
    }
}
