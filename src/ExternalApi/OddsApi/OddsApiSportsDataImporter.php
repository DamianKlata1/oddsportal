<?php

namespace App\ExternalApi\OddsApi;

use Exception;
use App\Entity\Sport;
use App\Entity\League;
use App\Entity\Region;
use App\ExternalApi\OddsApi\Helper\ImportResult;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\SportsDataImporterInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiRegionResolverInterface;

class OddsApiSportsDataImporter implements SportsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver,
        private readonly RegionLogoPathResolverInterface $regionLogoPathResolver,
        private readonly OddsApiRegionResolverInterface $regionResolver,
        private array $importedSports = [],
        private array $importedRegions = [],
        private array $importedLeagues = [],
    ) {
    }

    public function import(): ImportResult
    {
        $this->sportRepository->startTransaction();
        try {
            $sportsData = $this->oddsApiClient->fetchSportsData();
            $groupedBySport = array_reduce($sportsData, function ($carry, $item) {
                $carry[$item['group']][] = $item;
                return $carry;
            }, []);
            foreach ($groupedBySport as $sportName => $leaguesData) {
                $sport = $this->importSport($sportName);
                foreach ($leaguesData as $leagueData) {
                    $regionName = $this->regionResolver->resolveRegionName($leagueData);
                    $region = $this->importRegionForSport($regionName, $sport);
                    $leagueName = $leagueData['title'];
                    $this->importLeagueForRegion($leagueName, $region);
                }
            }
            $this->sportRepository->commitTransaction();
            return ImportResult::success([
                'sports' => $this->importedSports,
                'regions' => $this->importedRegions,
                'leagues' => $this->importedLeagues,
            ]);
        } catch (Exception $e) {
            $this->sportRepository->rollbackTransaction();
            return ImportResult::failure($e->getMessage());
        }
    }
    private function importSport(string $sportName): Sport
    {
        $sport = $this->sportRepository->findOneBy(['name' => $sportName]);
        if ($sport === null) {
            $sport = new Sport();
            $sport->setName($sportName);
            $sport->setLogoPath($this->sportLogoPathResolver->resolve($sportName));
            $this->sportRepository->save($sport, flush: true);
            $this->importedSports[] = $sport->getName();
        }
        return $sport;
    }
    private function importRegionForSport(string $regionName, Sport $sport): Region
    {
        $region = $this->regionRepository->findOneBy(['name' => $regionName, 'sport' => $sport]);
        if ($region === null) {
            $region = new Region();
            $region->setName($regionName);
            $region->setSport($sport);
            $region->setLogoPath($this->regionLogoPathResolver->resolve($regionName));
            $this->regionRepository->save($region, flush: true);
            $this->importedRegions[] = $region->getName();
        }
        return $region;
    }
    private function importLeagueForRegion(string $leagueName, Region $region): League
    {
        $league = $this->leagueRepository->findOneBy(['name' => $leagueName, 'region' => $region]);
        if ($league === null) {
            $league = new League();
            $league->setName($leagueName);
            $league->setRegion($region);
            $this->leagueRepository->save($league, flush: true);
            $this->importedLeagues[] = $league->getName();
        }
        return $league;
    }
}





