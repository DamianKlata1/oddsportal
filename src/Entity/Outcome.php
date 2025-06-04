<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OutcomeRepository;
use App\Entity\Interface\OutcomeInterface;

#[ORM\Entity(repositoryClass: OutcomeRepository::class)]
class Outcome implements OutcomeInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $market = null;

    #[ORM\ManyToOne(inversedBy: 'outcomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bookmaker $bookmaker = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastUpdate = null;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getMarket(): ?string
    {
        return $this->market;
    }

    public function setMarket(?string $market): static
    {
        $this->market = $market;

        return $this;
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

    public function getBookmaker(): ?Bookmaker
    {
        return $this->bookmaker;
    }

    public function setBookmaker(?Bookmaker $bookmaker): static
    {
        $this->bookmaker = $bookmaker;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeImmutable $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }
}
