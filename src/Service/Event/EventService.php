<?php

namespace App\Service\Event;

use App\Enum\BetRegion;
use App\Enum\MarketType;
use App\Enum\PriceFormat;
use App\DTO\Event\EventDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\Repository\Interface\BetRegionRepositoryInterface;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use App\Service\Interface\Outcome\OutcomeServiceInterface;

class EventService implements EventServiceInterface
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly BetRegionRepositoryInterface $betRegionRepository,
        private readonly OutcomeServiceInterface $outcomeService,

    ) {

    }
    /**
     * @return EventDTO[]
     */
    public function getEventsForLeague(int $leagueId, OutcomeFiltersDTO $outcomeFiltersDTO): array
    {
        $events = $this->eventRepository->findWithOutcomesByLeague($leagueId);

        $eventDTOs = [];

        foreach ($events as $event) {
            $outcomes = $this->outcomeService->filterOutcomesByMarketAndRegion(
                $event->getOutcomes(),
                MarketType::fromString($outcomeFiltersDTO->getMarket()),
                $this->betRegionRepository->findOneBy(['name' => $outcomeFiltersDTO->getBetRegion()])
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
