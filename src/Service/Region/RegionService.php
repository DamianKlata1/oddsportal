<?php

namespace App\Service\Region;


use App\DTO\League\LeagueDTO;
use App\DTO\Region\RegionWithLeaguesDTO;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Service\Entity\AbstractEntityService;
use App\Service\Interface\Region\RegionServiceInterface;

class RegionService extends AbstractEntityService implements RegionServiceInterface
{
    public function __construct(
        private readonly RegionRepositoryInterface $regionRepository,

    ) {
    }


    public function getRegionsWithActiveLeagues(int $sportId): array
    {
        $regions = $this->regionRepository->findWithActiveLeaguesBySport($sportId);

        $regionDtoList = [];

        foreach ($regions as $region) {
            $leagues = [];
            foreach ($region->getLeagues() as $league) {
                $leagues[] = new LeagueDTO($league->getId(), $league->getName());
            }

            if (count($leagues) > 0) {
                $regionDtoList[] = new RegionWithLeaguesDTO(
                    $region->getId(),
                    $region->getName(),
                    $region->getLogoPath(),
                    $leagues
                );
            }
        }
        return $regionDtoList;
    }



}
