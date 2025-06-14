<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BetRegionRepository;
use Doctrine\Common\Collections\Collection;
use App\Entity\Interface\BetRegionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['name'], message: 'There is already a region with this name.')]
#[ORM\Entity(repositoryClass: BetRegionRepository::class)]
class BetRegion implements BetRegionInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['bet_region_list'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Bookmaker::class, mappedBy: 'betRegions')]
    private Collection $bookmakers;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bet_region_list'])]
    private ?string $logoPath = null;

    public function __construct()
    {
        $this->bookmakers = new ArrayCollection();
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
     * @return Collection<int, Bookmaker>
     */
    public function getBookmakers(): Collection
    {
        return $this->bookmakers;
    }

    public function addBookmaker(Bookmaker $bookmaker): static
    {
        if (!$this->bookmakers->contains($bookmaker)) {
            $this->bookmakers->add($bookmaker);
            $bookmaker->addBetRegion($this);
        }

        return $this;
    }

    public function removeBookmaker(Bookmaker $bookmaker): static
    {
        if ($this->bookmakers->removeElement($bookmaker)) {
            $bookmaker->removeBetRegion($this);
        }

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(?string $logoPath): static
    {
        $this->logoPath = $logoPath;

        return $this;
    }
}
