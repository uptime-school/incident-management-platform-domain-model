namespace IncidentManagement.Domain.PostmortemReports.ValueObjects;

public readonly record struct PostmortemReportId(string Value)
{
    public static PostmortemReportId New() => new(Guid.NewGuid().ToString("N"));

    public override string ToString() => Value;
}
