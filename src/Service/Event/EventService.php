<?php

namespace App\Service\Event;

use App\Enum\BetRegion;
use App\Enum\MarketType;
use App\Enum\PriceFormat;
use App\DTO\Event\EventDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\Exception\ImportFailedException;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\BetRegionRepositoryInterface;
use App\Service\Interface\Outcome\OutcomeServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Service\Interface\Import\OddsDataImportSyncManagerInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;

class EventService implements EventServiceInterface
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly BetRegionRepositoryInterface $betRegionRepository,
        private readonly OutcomeServiceInterface $outcomeService,
        private readonly OddsDataImportSyncManagerInterface $oddsDataImportSyncManager,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly OddsApiOddsDataImporterInterface $oddsDataImporter,
        private readonly OddsApiClientInterface $oddsApiClient,

    ) {

    }
    /**
     * @return EventDTO[]
     */
    public function getEventsForLeague(int $leagueId, OutcomeFiltersDTO $outcomeFiltersDTO): array
    {
        
        $league = $this->leagueRepository->find($leagueId);
        $betRegion = $this->betRegionRepository->findOneBy(['name' => $outcomeFiltersDTO->getBetRegion()]);
        if($this->oddsDataImportSyncManager->isSyncRequired($league,$betRegion)) {
            $eventsData = $this->oddsApiClient->fetchOddsDataForLeague($league->getApiKey(), $betRegion->getName());
            $importResult = $this->oddsDataImporter->import($eventsData, $league, $betRegion);
            if (!$importResult->isSuccess()) {
                throw new ImportFailedException(
                    message: $importResult->getErrorMessage(),
                    code: Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }
            $this->oddsDataImportSyncManager->updateSyncStatus($league, $betRegion);
        }

        $events = $this->eventRepository->findWithOutcomesByLeague($leagueId);

        $eventDTOs = [];

        foreach ($events as $event) {
            $outcomes = $this->outcomeService->filterOutcomesByMarketAndRegion(
                $event->getOutcomes(),
                MarketType::fromString($outcomeFiltersDTO->getMarket()),
                $betRegion
            );
            $bestOutcomes = $this->outcomeService->getBestOutcomes($outcomes,PriceFormat::fromString($outcomeFiltersDTO->getPriceFormat()));
            
            $eventDTOs[] = new EventDTO(
                id: $event->getId(),
                homeTeam: $event->getHomeTeam(),
                awayTeam: $event->getAwayTeam(),
                commenceTime: $event->getCommenceTime(),
                bestOutcomes: $bestOutcomes
            );
        }
        return $eventDTOs;

    }

}
