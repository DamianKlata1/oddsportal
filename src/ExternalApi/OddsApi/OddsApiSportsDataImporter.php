<?php

namespace App\ExternalApi\OddsApi;

use App\Entity\Sport;
use App\Entity\League;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\SportsDataImporterInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiRegionResolverInterface;
use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;

class OddsApiSportsDataImporter implements SportsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver,
        private readonly OddsApiRegionResolverInterface $regionResolver
    ) {
    }

    public function import(): 
    {
        $sportsData = $this->oddsApiClient->fetchSportsData();

        $groupedBySport = array_reduce($sportsData, function ($carry, $item) {
            $carry[$item['group']][] = $item;
            return $carry;
        }, []);

        foreach ($groupedBySport as $sportName => $leaguesData) {
            $sport = $this->sportRepository->findOrCreate($sportName);

            foreach ($leaguesData as $leagueData) {
                $region = $this->regionRepository->findOrCreateForSport(
                    $this->regionResolver->resolveRegionName($leagueData),
                    $sport->getId()
                );
                $leagueName = $leagueData['title'];
                $league = $this->leagueRepository->findOrCreateForRegion($leagueName, $region->getId());
            }
        }
    }
}
