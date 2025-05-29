<?php

namespace App\DTO\Event;

use App\DTO\League\LeagueDTO;
use App\DTO\Outcome\OutcomeDTO;
use App\DTO\Region\RegionDTO;
use App\DTO\Sport\SportDTO;
use Symfony\Component\Validator\Constraints as Assert;

class EventDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('integer')]
        #[Assert\Positive()]
        private readonly int $id,
        private readonly LeagueDTO $league,
        private readonly RegionDTO $region,
        private readonly SportDTO $sport,
        #[Assert\NotBlank()]
        private readonly \DateTimeImmutable $commenceTime,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        private readonly ?string $homeTeam,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        private readonly ?string $awayTeam,
        /** 
         * @var OutcomeDTO[]
         */
        private readonly array $bestOutcomes,
    ) {
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getCommenceTime(): \DateTimeImmutable
    {
        return $this->commenceTime;
    }
    public function getHomeTeam(): ?string
    {
        return $this->homeTeam;
    }
    public function getAwayTeam(): ?string
    {
        return $this->awayTeam;
    }
    /**
     * @return OutcomeDTO[]
     */
    public function getBestOutcomes(): array
    {
        return $this->bestOutcomes;
    }

    public function getLeague(): LeagueDTO
    {
        return $this->league;
    }

    public function getRegion(): RegionDTO
    {
        return $this->region;
    }

    public function getSport()
    {
        return $this->sport;
    }
}
