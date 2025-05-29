<?php

namespace App\ExternalApi\OddsApi;

use Exception;
use App\Entity\Sport;
use App\Entity\League;
use App\Entity\Region;
use App\ExternalApi\OddsApi\Helper\ImportResult;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Repository\Interface\SportRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiRegionResolverInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiSportsDataImporterInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;

class OddsApiSportsDataImporter implements OddsApiSportsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver,
        private readonly RegionLogoPathResolverInterface $regionLogoPathResolver,
        private readonly OddsApiRegionResolverInterface $regionResolver,
        private readonly ValidationServiceInterface $validationService,
        private array $importedSports = [],
        private array $importedRegions = [],
        private array $importedLeagues = [],
    ) {
    }
    /**
     * @param OddsApiSportsDataDTO[] $sportsData
     */
    public function import(array $sportsData): ImportResult
    {
        $this->sportRepository->startTransaction();
        try {
            $this->validationService->validateAll($sportsData);
            $groupedBySport = array_reduce($sportsData, function ($carry, $item) {
                $sportName = $item->getGroup();
                $carry[$sportName][] = $item;
                return $carry;
            }, []);
            foreach ($groupedBySport as $sportName => $sportDataDtos) {
                $sport = $this->importSport($sportName);
                foreach ($sportDataDtos as $sportDataDto) {
                    $regionName = $this->regionResolver->resolve($sportDataDto);
                    $region = $this->importRegionForSport($regionName, $sport);
                    $this->importLeagueForRegion(
                        $sportDataDto->getTitle(),
                        $region,
                        $sportDataDto->getKey(),
                        $sportDataDto->isActive()
                    );
                }
            }
            $this->sportRepository->flush();
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
    private function importLeagueForRegion(string $leagueName, Region $region, string $apiKey, bool $isActive): League
    {
        $league = $this->leagueRepository->findOneBy(['name' => $leagueName, 'region' => $region]);
        if ($league === null) {
            $league = new League();
            $league->setName($leagueName);
            $league->setRegion($region);
            $league->setApiKey($apiKey);
            $league->setActive($isActive);
            $this->leagueRepository->save($league, flush: true);
            $this->importedLeagues[] = $league->getName();
        } else {
            if ($league->getApiKey() !== $apiKey) {
                $league->setApiKey($apiKey);
            }
            if ($league->isActive() !== $isActive) {
                $league->setActive($isActive);
            }
        }
        return $league;
    }
}





