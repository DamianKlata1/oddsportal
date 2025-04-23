<?php

namespace App\ExternalApi\OddsApi;

use Exception;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\SportsDataImporterInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiRegionResolverInterface;

class OddsApiSportsDataImporter implements SportsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver,
        private readonly OddsApiRegionResolverInterface $regionResolver,
    ) {
    }

    public function import(): void
    {//przetestowac czy mozna przekazac obiekt do findby
        try {
            $this->sportRepository->startTransaction();
            
            $sportsData = $this->oddsApiClient->fetchSportsData();

            $groupedBySport = array_reduce($sportsData, function ($carry, $item) {
                $carry[$item['group']][] = $item;
                return $carry;
            }, []);

            foreach ($groupedBySport as $sportName => $leaguesData) {
                $sport = $this->sportRepository->findOrCreate($sportName);
                $this->sportRepository->save($sport, flush: true);

                foreach ($leaguesData as $leagueData) {
                    $region = $this->regionRepository->findOrCreateForSport(
                        $this->regionResolver->resolveRegionName($leagueData),
                        $sport->getId()
                    );
                    $this->regionRepository->save($region, flush: true);

                    $leagueName = $leagueData['title'];
                    $league = $this->leagueRepository->findOrCreateForRegion($leagueName, $region->getId());
                    $this->leagueRepository->save($league, flush: true);
                }
            }
            $this->sportRepository->commitTransaction();
        }catch(Exception $e) {
            $this->sportRepository->rollbackTransaction();
            throw $e;
        }
    }
}



    

