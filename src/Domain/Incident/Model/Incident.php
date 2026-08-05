<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model;

use DateTime;
use App\Domain\Incident\Exception\IncidentNotResolvedOrClosedException;
use App\Domain\Incident\Exception\MissingOwnerOrTeamException;
use App\Domain\Incident\Exception\PostmortemReportAlreadyExistsException;
use App\Domain\Incident\Model\Status\CancelledStatus;
use App\Domain\Incident\Model\Status\ClosedStatus;
use App\Domain\Incident\Model\Status\IncidentStatus;
use App\Domain\Incident\Model\Status\InvestigatingStatus;
use App\Domain\Incident\Model\Status\MitigatingStatus;
use App\Domain\Incident\Model\Status\OpenStatus;
use App\Domain\Incident\Model\Status\ResolvedStatus;
use App\Domain\IncidentParticipant\Model\IncidentParticipant;
use App\Domain\IncidentParticipant\Model\ParticipantRole;
use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use App\Domain\PostmortemReport\Model\PostmortemReport;
use App\Domain\PostmortemReport\Model\ReportFile;
use App\Domain\Team\Model\Team;
use App\Domain\TimelineEvent\Model\TimelineEvent;
use App\Domain\TimelineEvent\Model\TimelineEventType;
use App\Domain\User\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Incident extends AbstractModel
{
    #[ORM\Column(name: 'status', type: 'string', length: 20)]
    private string $statusValue;

    public function __construct(
        string $id,
        #[ORM\Column]
        private readonly string $title,
        #[ORM\Column(type: 'text')]
        private readonly string $description,
        private IncidentStatus $status,
        #[ORM\Column(enumType: Severity::class)]
        private readonly Severity $severity,
        #[ORM\Column]
        private readonly DateTime $declaredAt,
        #[ORM\Column(nullable: true)]
        private ?DateTime $resolvedAt = null,
        #[ORM\OneToMany(targetEntity: IncidentParticipant::class, mappedBy: 'incident')]
        private readonly Collection $participants = new ArrayCollection(),
        #[ORM\OneToOne(targetEntity: PostmortemReport::class, mappedBy: 'incident')]
        private ?PostmortemReport $postmortemReport = null,
        #[ORM\OneToMany(targetEntity: TimelineEvent::class, mappedBy: 'incident')]
        private readonly Collection $timelineEvents = new ArrayCollection(),
        #[ORM\Column(length: 36, nullable: true)]
        private ?string $assignedTeamId = null,
    ) {
        parent::__construct(new Id($id));
        $this->syncStatusValue();
    }

    public function investigate(?User $actor = null): void
    {
        if ($this->hasTeam() || $this->hasOwner()) {
            $this->status = $this->status->investigate();
            $this->resolvedAt = null;
            $this->syncStatusValue();
            $this->recordStatusChange($actor);
        } else {
            throw new MissingOwnerOrTeamException($this->getId());
        }
    }

    public function mitigate(?User $actor = null): void
    {
        $this->status = $this->status->mitigate();
        $this->syncStatusValue();
        $this->recordStatusChange($actor);
    }

    public function resolve(?User $actor = null): void
    {
        $this->status = $this->status->resolve();
        $this->resolvedAt = new DateTime();
        $this->syncStatusValue();
        $this->recordStatusChange($actor);
    }

    public function close(?User $actor = null): void
    {
        $this->status = $this->status->close();
        $this->syncStatusValue();
        $this->recordStatusChange($actor);
    }

    public function cancel(?User $actor = null): void
    {
        $this->status = $this->status->cancel();
        $this->syncStatusValue();
        $this->recordStatusChange($actor);
    }

    private function recordStatusChange(?User $actor): void
    {
        $this->addTimelineEvent(TimelineEvent::create(
            TimelineEventType::StatusChanged,
            $this->status->getTransitionMessage(),
            new DateTime(),
            $actor === null,
            $this,
            $actor
        ));
    }

    public function hasOwner(): bool
    {
        foreach ($this->participants as $participant) {
            if ($participant->getRole() === ParticipantRole::owner) {
                return true;
            }
        }

        return false;
    }

    public function hasTeam(): bool
    {
        return $this->assignedTeamId !== null;
    }

    public function getAssignedTeamId(): ?string
    {
        return $this->assignedTeamId;
    }

    public function assignTeam(Team $team): void
    {
        $this->assignedTeamId = $team->getId();
    }

    public function addParticipant(IncidentParticipant $participant): void
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }
    }

    public function addTimelineEvent(TimelineEvent $timelineEvent): void
    {
        if (!$this->timelineEvents->contains($timelineEvent)) {
            $this->timelineEvents->add($timelineEvent);
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): IncidentStatus
    {
        return $this->status;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getDeclaredAt(): DateTime
    {
        return $this->declaredAt;
    }

    public function getResolvedAt(): ?DateTime
    {
        return $this->resolvedAt;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getPostmortemReport(): ?PostmortemReport
    {
        return $this->postmortemReport;
    }

    public function assertCanHavePostmortemReport(): void
    {
        if ($this->postmortemReport !== null) {
            throw new PostmortemReportAlreadyExistsException($this->getId());
        }

        if (!($this->status instanceof ResolvedStatus || $this->status instanceof ClosedStatus)) {
            throw new IncidentNotResolvedOrClosedException($this->getId());
        }
    }

    public function setPostmortemReport(PostmortemReport $postmortemReport): void
    {
        $this->postmortemReport = $postmortemReport;
    }

    public function createPostmortemReport(
        string $summary,
        DateTime $generatedAt,
        ReportFile $file
    ): PostmortemReport {
        $this->assertCanHavePostmortemReport();

        $report = PostmortemReport::create($summary, $generatedAt, $file, $this);

        $this->setPostmortemReport($report);

        return $report;
    }

    public function getTimelineEvents(): Collection
    {
        return $this->timelineEvents;
    }

    #[ORM\PostLoad]
    public function hydrateStatus(): void
    {
        $this->status = match ($this->statusValue) {
            'open' => new OpenStatus(),
            'investigating' => new InvestigatingStatus(),
            'mitigating' => new MitigatingStatus(),
            'resolved' => new ResolvedStatus(),
            'closed' => new ClosedStatus(),
            'cancelled' => new CancelledStatus(),
        };
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    private function syncStatusValue(): void
    {
        $this->statusValue = match (true) {
            $this->status instanceof OpenStatus => 'open',
            $this->status instanceof InvestigatingStatus => 'investigating',
            $this->status instanceof MitigatingStatus => 'mitigating',
            $this->status instanceof ResolvedStatus => 'resolved',
            $this->status instanceof ClosedStatus => 'closed',
            $this->status instanceof CancelledStatus => 'cancelled',
        };
    }
}