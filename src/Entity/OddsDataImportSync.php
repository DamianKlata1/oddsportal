<?php

namespace App\Entity;

use App\Repository\OddsDataImportSyncRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OddsDataImportSyncRepository::class)]
class OddsDataImportSync
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?League $league = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?BetRegion $betRegion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastImportedAt = null;

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
