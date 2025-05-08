<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookmakerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['name'], message: 'There is already a bookmaker with this name.')]
#[ORM\Entity(repositoryClass: BookmakerRepository::class)]
class Bookmaker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: BetRegion::class, inversedBy: 'bookmakers')]
    private Collection $betRegion;

    public function __construct()
    {
        $this->betRegion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BetRegion>
     */
    public function getBetRegion(): Collection
    {
        return $this->betRegion;
    }

    public function addBetRegion(BetRegion $betRegion): static
    {
        if (!$this->betRegion->contains($betRegion)) {
            $this->betRegion->add($betRegion);
        }

        return $this;
    }

    public function removeBetRegion(BetRegion $betRegion): static
    {
        $this->betRegion->removeElement($betRegion);

        return $this;
    }
}
