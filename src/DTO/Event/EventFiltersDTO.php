<?php

namespace App\DTO\Event;
use Symfony\Component\Validator\Constraints as Assert;

class EventFiltersDTO
{
    public function __construct(
        #[Assert\Type('int')]
        #[Assert\Range(min: 1)]
        private readonly ?int $leagueId = null,
        #[Assert\Type('int')]
        #[Assert\Range(min: 1)]
        private readonly ?int $sportId = null,
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        private readonly ?string $name = null,
        #[Assert\Type('string')]
        private readonly ?string $date = null,
    ) {
    }
    public function getName(): string|null
    {
        return $this->name;
    }
    public function getDate(): string|null
    {
        return $this->date;
    }
    public function getSportId(): int|null
    {
        return $this->sportId;
    }
    public function getLeagueId(): int|null
    {
        return $this->leagueId;
    }
}
