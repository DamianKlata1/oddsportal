<?php

namespace App\Service\League;

use App\Service\Helper\DeleteResult;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Service\Interface\Helper\DeleteResultInterface;
use App\Service\Interface\League\LeagueServiceInterface;

class LeagueService implements LeagueServiceInterface
{
    public function __construct(
        private readonly LeagueRepositoryInterface $leagueRepository
    ) {
    }
    /**
     * Summary of removeOutdatedLeagues
     * @param OddsApiSportsDataDTO[] $sportsDataDTOs
     * @return DeleteResult
     */
    public function removeOutdatedLeagues(array $sportsDataDTOs): DeleteResultInterface
    {
        try {
            $this->leagueRepository->startTransaction();

            $allLeagues = $this->leagueRepository->findAll();
            $apiKeys = array_map(fn($data) => $data->getKey(), $sportsDataDTOs);

            $deletedLeagues = [];
            foreach ($allLeagues as $league) {
                if (!in_array($league->getApiKey(), $apiKeys, true)) {
                    $deletedLeagues[] = [
                        'league' => $league->getName(),
                        'region' => $league->getRegion()->getName(),
                        'sport' => $league->getRegion()->getSport()->getName(),
                    ];
                    dd($league->getName(), $league->getRegion()->getName(), $league->getRegion()->getSport()->getName());
                    $this->leagueRepository->remove($league);
                }   
            }
            $this->leagueRepository->flush();
            $this->leagueRepository->commitTransaction();
            return DeleteResult::success(
                [
                    'leagues' => $deletedLeagues,
                ]
            );
        } catch (\Exception $e) {
            $this->leagueRepository->rollbackTransaction();
            return DeleteResult::failure(
                $e->getMessage()
            );
        }
    }
}
