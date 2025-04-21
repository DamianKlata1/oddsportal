<?php

namespace App\ExternalApi\OddsApi;

use App\Entity\Sport;
use App\Entity\League;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\SportsDataImporterInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use App\ExternalApi\OddsApi\Interface\OddsApiRegionMapperInterface;
use App\ExternalApi\ThesportsdbApi\Interface\SportLogoPathResolverInterface;

class OddsApiSportsDataImporter implements SportsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver,
        private readonly OddsApiRegionMapperInterface $regionMapper
    ) {
    }

    public function import(): void
    {
        $sportsData = $this->oddsApiClient->fetchSportsData();

        foreach ($sportsData as $datum) {
            $sportName = $datum['group'];
            if (!$this->sportRepository->checkIfSportsNameExists($sportName)) {
                $sport = new Sport();
                $sport->setName($sportName);
                $sport->setLogoPath($this->sportLogoPathResolver->resolve($sportName));
                $this->regionMapper->mapRegionsToSport($sport, $sportsData);
                $this->sportRepository->save($sport);
            }
            $leagueName = $datum['title'];
            if (!$this->leagueRepository->checkIfLeagueNameExists($leagueName)) {
                $league = new League();
                $league->setName($leagueName);
                $league->setLevel(1);
                $this->regionMapper->mapRegionToLeague($league, $sportsData);
                $this->leagueRepository->save($league);
            }
        }
    }
}