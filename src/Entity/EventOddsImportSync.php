<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventOddsImportSyncRepository;
use App\Entity\Interface\EventOddsImportSyncInterface;

#[ORM\Entity(repositoryClass: EventOddsImportSyncRepository::class)]
class EventOddsImportSync implements EventOddsImportSyncInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventOddsImportSyncs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?BetRegion $betRegion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastImportedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getBetRegion(): ?BetRegion
    {
        return $this->betRegion;
    }

    public function setBetRegion(?BetRegion $betRegion): static
    {
        $this->betRegion = $betRegion;

        return $this;
    }

    public function getLastImportedAt(): ?\DateTimeImmutable
    {
        return $this->lastImportedAt;
    }

    public function setLastImportedAt(\DateTimeImmutable $lastImportedAt): static
    {
        $this->lastImportedAt = $lastImportedAt;

        return $this;
    }
}
