<?php

namespace App\Service\Event;

use App\DTO\Sport\SportDTO;
use App\Entity\League;
use App\Service\Entity\AbstractEntityService;
use DateTimeImmutable;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Enum\PriceFormat;
use Pagerfanta\Pagerfanta;
use App\DTO\Event\EventDTO;
use App\DTO\League\LeagueDTO;
use App\DTO\Region\RegionDTO;
use App\Enum\DateFilterKeyword;
use App\DTO\Event\EventFiltersDTO;
use App\Service\Helper\DeleteResult;
use App\DTO\Pagination\PaginationDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\DTO\Event\EventListResponseDTO;
use App\Exception\ImportFailedException;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use App\DTO\Pagination\PaginationResponseDTO;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use Pagerfanta\Exception\NotValidCurrentPageException;
use App\Service\Interface\Helper\DeleteResultInterface;
use App\Service\Interface\Outcome\OutcomeServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\Interface\Import\OddsDataImportSyncManagerInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;
use App\Service\Interface\League\LeagueServiceInterface;

class EventService extends AbstractEntityService implements EventServiceInterface
{

    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly BetRegionServiceInterface $betRegionService,
        private readonly LeagueServiceInterface $leagueService,
        private readonly OutcomeServiceInterface $outcomeService,
        private readonly OddsDataImportSyncManagerInterface $oddsDataImportSyncManager,
        private readonly OddsApiOddsDataImporterInterface $oddsDataImporter,
        private readonly OddsApiClientInterface $oddsApiClient,
    ) {
        parent::__construct($eventRepository);
    }

    /**
     * Retrieves paginated events for a specific league, applying outcome filters.
     *
     * @param int $leagueId 
     * @param OutcomeFiltersDTO $outcomeFiltersDTO Filters for outcomes (market, region, price format).
     * @param PaginationDTO $paginationDTO Pagination parameters (page, limit).
     * @return EventListResponseDTO An object containing the list of event DTOs and pagination information.
     * @throws NotFoundHttpException If the league or bet region is not found.
     * @throws ImportFailedException If the odds data import fails during synchronization.
     */
    public function getEvents(EventFiltersDTO $eventFiltersDTO, OutcomeFiltersDTO $outcomeFiltersDTO, PaginationDTO $paginationDTO): EventListResponseDTO
    {
        $league = $eventFiltersDTO->getLeagueId() ? $this->leagueService->findOrFail($eventFiltersDTO->getLeagueId()) : null;

        $betRegion = $this->betRegionService->findOrFailBy(['name' => $outcomeFiltersDTO->getBetRegion()]);

        $priceFormat = PriceFormat::fromString($outcomeFiltersDTO->getPriceFormat());

        $this->synchronizeLeagueOddsData($league, $betRegion);

        $dateWindow = $this->calculateDateWindow(
            $eventFiltersDTO->getDate() !== null
            ? DateFilterKeyword::fromString($eventFiltersDTO->getDate())
            : null
        );
        
        $paginatedEvents = $this->getPaginatedEvents(
            $eventFiltersDTO->getLeagueId(),
            $eventFiltersDTO->getName(),
            $dateWindow['start'],
            $dateWindow['end'],
            $paginationDTO
        );


        $eventDTOs = $this->getEventsWithBestOutcomes(
            $paginatedEvents->getCurrentPageResults(),
            $betRegion,
            $priceFormat
        );

        return $this->buildEventListResponse($paginatedEvents, $eventDTOs);
    }

    public function deletePastEvents(): DeleteResultInterface
    {
        try {
            $this->eventRepository->startTransaction();

            $eventsData = [];
            $events = $this->eventRepository->findPastEvents();
            foreach ($events as $event) {
                $eventsData[] = [
                    'homeTeam' => $event->getHomeTeam(),
                    'awayTeam' => $event->getAwayTeam(),
                    'commenceTime' => $event->getCommenceTime(),
                ];
                $this->eventRepository->remove($event);
            }

            $this->eventRepository->flush();
            $this->eventRepository->commitTransaction();
            return DeleteResult::success(
                [
                    'events' => $eventsData,
                ]
            );
        } catch (\Exception $e) {
            $this->eventRepository->rollbackTransaction();
            return DeleteResult::failure(
                $e->getMessage()
            );
        }
    }

    private function synchronizeLeagueOddsData(?League $league, BetRegion $betRegion): void
    {
        if(!$league) {
            //TODO : Handle case where league is not provided
            return;
        }
        if ($this->oddsDataImportSyncManager->isSyncRequired($league, $betRegion)) {
            $eventsData = $this->oddsApiClient->fetchOddsDataForLeague(
                $league->getApiKey(),
                $betRegion->getName(),
                $league->getRegion()->getName() === 'Outrights' ? MarketType::OUTRIGHTS : MarketType::H2H
            );
            $importResult = $this->oddsDataImporter->import($eventsData, $league, $betRegion);
            if (!$importResult->isSuccess()) {
                throw new ImportFailedException(
                    message: $importResult->getErrorMessage() ?? 'Unknown import error.',
                    code: Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }
            $this->oddsDataImportSyncManager->updateSyncStatus($league, $betRegion);
        }
    }
    private function getPaginatedEvents(
        ?int $leagueId,
        ?string $nameFilter,
        ?DateTimeImmutable $filterStartDate,
        ?DateTimeImmutable $filterEndDate,
        PaginationDTO $paginationDTO
    ): Pagerfanta {
        $qb = $this->eventRepository->findByFiltersQueryBuilder(
            $leagueId,
            $nameFilter,
            $filterStartDate,
            $filterEndDate
        );
        $adapter = new QueryAdapter($qb);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($paginationDTO->getLimit());

        try {
            $pager->setCurrentPage($paginationDTO->getPage());
        } catch (NotValidCurrentPageException $e) {
            $pager->setCurrentPage(1);
        }
        return $pager;
    }

    /**
     *
     * @return EventDTO[]
     */
    private function getEventsWithBestOutcomes(iterable $events, BetRegion $betRegion, PriceFormat $priceFormat): array
    {
        $eventDTOs = [];
        foreach ($events as $event) {
            $outcomes = $this->outcomeService->filterOutcomesByMarketsAndRegion(
                $event->getOutcomes(),
                [
                    MarketType::H2H,
                    MarketType::OUTRIGHTS
                ],
                $betRegion
            );
            $bestOutcomes = $this->outcomeService->getBestOutcomes(
                $outcomes,
                $priceFormat
            );
            $eventDTOs[] = new EventDTO(
                id: $event->getId(),
                homeTeam: $event->getHomeTeam(),
                awayTeam: $event->getAwayTeam(),
                commenceTime: $event->getCommenceTime(),
                bestOutcomes: $bestOutcomes,
                league: new LeagueDTO(
                    id: $event->getLeague()->getId(),
                    name: $event->getLeague()->getName(),
                    logoPath: $event->getLeague()->getLogoPath()
                ),
                region: new RegionDTO(
                    id: $event->getLeague()->getRegion()->getId(),
                    name: $event->getLeague()->getRegion()->getName(),
                    logoPath: $event->getLeague()->getRegion()->getLogoPath()
                ),
                sport: new SportDTO(
                    id: $event->getLeague()->getRegion()->getSport()->getId(),
                    name: $event->getLeague()->getRegion()->getSport()->getName(),
                    logoPath: $event->getLeague()->getRegion()->getSport()->getLogoPath()
                )
            );
        }
        return $eventDTOs;
    }

    private function buildEventListResponse(Pagerfanta $pager, array $eventDTOs): EventListResponseDTO
    {
        return new EventListResponseDTO(
            $eventDTOs,
            new PaginationResponseDTO(
                page: $pager->getCurrentPage(),
                limit: $pager->getMaxPerPage(),
                total: $pager->getNbResults(),
                pages: $pager->getNbPages()
            )
        );
    }

    private function calculateDateWindow(?DateFilterKeyword $dateKeyword): array
    {
        $windowStart = null;
        $windowEnd = null;
        $now = new DateTimeImmutable();

        if ($dateKeyword === null) {
            return ['start' => null, 'end' => null];
        }

        switch ($dateKeyword) {
            case DateFilterKeyword::TODAY:
                $windowStart = $now;
                $windowEnd = $now->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::TOMORROW:
                $windowStart = $now->modify('+1 day')->setTime(0, 0, 0);
                $windowEnd = $now->modify('+1 day')->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::THIS_WEEK:

                $windowStart = $now;
                $windowEnd = $now->modify('sunday this week')->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::NEXT_7_DAYS:
                $windowStart = $now;
                $windowEnd = $now->modify('+6 days')->setTime(23, 59, 59, 999999);
                break;

        }

        return ['start' => $windowStart, 'end' => $windowEnd];
    }
}
