<?php

namespace App\DTO\ExternalApi\OddsApi;
use Symfony\Component\Validator\Constraints as Assert;

class OddsApiEventDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $id,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $commenceTime,
        #[Assert\Type('string')]
        private readonly ?string $homeTeam,
        #[Assert\Type('string')]
        private readonly ?string $awayTeam,
        /**
         * @var OddsApiBookmakerDTO[]
         */
        private readonly array $bookmakers,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHomeTeam(): string|null
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string|null
    {
        return $this->awayTeam;
    }
    public function getCommenceTime(): string
    {
        return $this->commenceTime;
    }

    /**
     * @return  OddsApiBookmakerDTO[]
     */
    public function getBookmakers(): array
    {
        return $this->bookmakers;
    }
}
