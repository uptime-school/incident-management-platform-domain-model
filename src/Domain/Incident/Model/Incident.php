<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model;

use DateTimeImmutable;
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
use App\Domain\TimelineEvent\Model\TimelineEvent;
use App\Domain\TimelineEvent\Model\TimelineEventType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Incident extends AbstractModel
{
    private IncidentStatus $status;

    public function __construct(
        string $id,
        #[ORM\Column]
        private string $title,
        #[ORM\Column(type: 'text')]
        private string $description,
        #[ORM\Column(enumType: Severity::class)]
        private Severity $severity,
        #[ORM\Column]
        private readonly DateTimeImmutable $createdAt,
        #[ORM\Column(nullable: true)]
        private ?DateTimeImmutable $resolvedAt = null,
        #[ORM\OneToMany(targetEntity: IncidentParticipant::class, mappedBy: 'incident')]
        private Collection $participants = new ArrayCollection(),
        #[ORM\OneToOne(targetEntity: PostmortemReport::class, mappedBy: 'incident')]
        private ?PostmortemReport $postmortemReport = null,
        #[ORM\OneToMany(targetEntity: TimelineEvent::class, mappedBy: 'incident')]
        private Collection $timelineEvents = new ArrayCollection(),
        #[ORM\Column(length: 36, nullable: true)]
        private ?string $assignedTeamId = null,
        #[ORM\Column(name: 'status', type: 'string', length: 20)]
        private string $statusValue = '',
    ) {
        parent::__construct(new Id($id));
        $this->status = new OpenStatus();
        $this->syncStatusValue();
    }

    public static function create(
        string $title,
        string $description,
        Severity $severity,
        DateTimeImmutable $createdAt
    ): self {
        return new self(
            Uuid::v4()->toRfc4122(),
            $title,
            $description,
            new OpenStatus(),
            $severity,
            $createdAt
        );
    }

    public function investigate(?string $actorId = null): void
    {
        if ($this->hasTeam() || $this->hasOwner()) {
            $this->status = $this->status->investigate();
            $this->resolvedAt = null;
            $this->syncStatusValue();
            $this->recordStatusChange($actorId);
        } else {
            throw new MissingOwnerOrTeamException($this->getId());
        }
    }

    public function mitigate(?string $actorId = null): void
    {
        $this->status = $this->status->mitigate();
        $this->syncStatusValue();
        $this->recordStatusChange($actorId);
    }

    public function resolve(?string $actorId = null): void
    {
        $this->status = $this->status->resolve();
        $this->resolvedAt = new DateTimeImmutable();
        $this->syncStatusValue();
        $this->recordStatusChange($actorId);
    }

    public function close(?string $actorId = null): void
    {
        $this->status = $this->status->close();
        $this->syncStatusValue();
        $this->recordStatusChange($actorId);
    }

    public function cancel(?string $actorId = null): void
    {
        $this->status = $this->status->cancel();
        $this->syncStatusValue();
        $this->recordStatusChange($actorId);
    }

    private function recordStatusChange(?string $actorId): void
    {
        $this->addTimelineEvent(TimelineEvent::create(
            TimelineEventType::StatusChanged,
            $this->status->getTransitionMessage(),
            new DateTimeImmutable(),
            $actorId === null,
            $this,
            $actorId
        ));
    }

    public function hasOwner(): bool
    {
        foreach ($this->participants as $participant) {
            if ($participant->getRole() === ParticipantRole::Owner) {
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

    public function assignTeam(string $teamId): void
    {
        $this->assignedTeamId = $teamId;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getResolvedAt(): ?DateTimeImmutable
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
        DateTimeImmutable $generatedAt,
        string $fileId
    ): PostmortemReport {
        $this->assertCanHavePostmortemReport();

        $report = PostmortemReport::create($summary, $generatedAt, $fileId, $this);

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
