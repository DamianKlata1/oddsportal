<?php

namespace App\DTO\Outcome;
use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\Bookmaker\BookmakerDTO;

class OutcomeDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        private readonly int $id,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        private readonly string $name,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $price,
        private readonly BookmakerDTO $bookmaker,
        #[Assert\NotBlank()]
        private readonly \DateTimeImmutable $lastUpdate,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getLastUpdate(): \DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function getBookmaker(): BookmakerDTO
    {
        return $this->bookmaker;
    }
}
