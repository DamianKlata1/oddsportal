<?php

namespace App\DTO\Region;
use App\DTO\League\LeagueDTO;
use Symfony\Component\Validator\Constraints as Assert;


class RegionWithLeaguesDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'integer')]
        private int $id,
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'string')]
        private string $name,
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'string')]
        #[Assert\Url()]
        private string $logoPath,
        /** @var LeagueDTO[] */
        private array $leagues
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

    public function getLogoPath(): string
    {
        return $this->logoPath;
    }


    /**
     * @return LeagueDTO[]
     */
    public function getLeagues(): array
    {
        return $this->leagues;
    }
}