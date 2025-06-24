<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use App\Entity\Interface\EventInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event implements EventInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?League $league = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homeTeam = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $awayTeam = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $commenceTime = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Outcome::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $outcomes;

    #[ORM\Column(length: 255)]
    private ?string $apiId = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventOddsImportSync::class, orphanRemoval: true)]
    private Collection $eventOddsImportSyncs;

    public function __construct()
    {
        $this->outcomes = new ArrayCollection();
        $this->eventOddsImportSyncs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): static
    {
        $this->league = $league;

        return $this;
    }

    public function getHomeTeam(): ?string
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?string $homeTeam): static
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?string
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?string $awayTeam): static
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getCommenceTime(): ?\DateTimeImmutable
    {
        return $this->commenceTime;
    }

    public function setCommenceTime(\DateTimeImmutable $commenceTime): static
    {
        $this->commenceTime = $commenceTime;

        return $this;
    }

    /**
     * @return Collection<int, Outcome>
     */
    public function getOutcomes(): Collection
    {
        return $this->outcomes;
    }

    public function addOutcome(Outcome $outcome): static
    {
        if (!$this->outcomes->contains($outcome)) {
            $this->outcomes->add($outcome);
            $outcome->setEvent($this);
        }

        return $this;
    }

    public function removeOutcome(Outcome $outcome): static
    {
        if ($this->outcomes->removeElement($outcome)) {
            // set the owning side to null (unless already changed)
            if ($outcome->getEvent() === $this) {
                $outcome->setEvent(null);
            }
        }

        return $this;
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(string $apiId): static
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * @return Collection<int, EventOddsImportSync>
     */
    public function getEventOddsImportSyncs(): Collection
    {
        return $this->eventOddsImportSyncs;
    }

    public function addEventOddsImportSync(EventOddsImportSync $eventOddsImportSync): static
    {
        if (!$this->eventOddsImportSyncs->contains($eventOddsImportSync)) {
            $this->eventOddsImportSyncs->add($eventOddsImportSync);
            $eventOddsImportSync->setEvent($this);
        }

        return $this;
    }

    public function removeEventOddsImportSync(EventOddsImportSync $eventOddsImportSync): static
    {
        if ($this->eventOddsImportSyncs->removeElement($eventOddsImportSync)) {
            // set the owning side to null (unless already changed)
            if ($eventOddsImportSync->getEvent() === $this) {
                $eventOddsImportSync->setEvent(null);
            }
        }

        return $this;
    }
}
