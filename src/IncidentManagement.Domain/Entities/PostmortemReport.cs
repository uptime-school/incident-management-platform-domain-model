using IncidentManagement.Domain.Common;
using IncidentManagement.Domain.ValueObjects;

namespace IncidentManagement.Domain.Entities;

public sealed class PostmortemReport : Entity
{
    public Guid IncidentId { get; }
    public string Summary { get; }
    public DateTimeOffset GeneratedAt { get; }
    public ReportFile? File { get; }

    public PostmortemReport(
        Guid id,
        Guid incidentId,
        string summary,
        DateTimeOffset generatedAt,
        ReportFile? file = null) : base(id)
    {
        if (string.IsNullOrWhiteSpace(summary))
            throw new ArgumentException("Summary cannot be empty.", nameof(summary));

        IncidentId = incidentId;
        Summary = summary;
        GeneratedAt = generatedAt;
        File = file;
    }
}
