<?php

namespace App\Service\Event;

use App\DTO\Outcome\OutcomeListResponseDTO;
use App\Entity\Event;
use App\Entity\League;
use DateTimeImmutable;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Enum\PriceFormat;
use Pagerfanta\Pagerfanta;
use App\DTO\Event\EventDTO;
use App\DTO\Sport\SportDTO;
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
use App\Service\Entity\AbstractEntityService;
use App\Service\Import\EventOddsImportSyncService;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use Pagerfanta\Exception\NotValidCurrentPageException;
use App\Service\Interface\Helper\DeleteResultInterface;
use App\Service\Interface\League\LeagueServiceInterface;
use App\Service\Interface\Outcome\OutcomeServiceInterface;
use App\Service\Interface\DateService\DateServiceInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\Interface\Import\EventOddsImportSyncServiceInterface;
use App\Service\Interface\Import\LeagueOddsImportSyncServiceInterface;

class EventService extends AbstractEntityService implements EventServiceInterface
{

    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly BetRegionServiceInterface $betRegionService,
        private readonly LeagueServiceInterface $leagueService,
        private readonly OutcomeServiceInterface $outcomeService,
        private readonly LeagueOddsImportSyncServiceInterface $leagueOddsImportSyncService,
        private readonly EventOddsImportSyncServiceInterface $eventOddsImportSyncService,
        private readonly DateServiceInterface $dateService

    ) {
        parent::__construct($eventRepository);
    }

    /**
     * @throws NotFoundHttpException If the league or bet region is not found.
     * @throws ImportFailedException If the odds data import fails during synchronization.
     */
    public function getEvents(EventFiltersDTO $eventFiltersDTO, OutcomeFiltersDTO $outcomeFiltersDTO, PaginationDTO $paginationDTO): EventListResponseDTO
    {
        $league = $eventFiltersDTO->getLeagueId() ? $this->leagueService->findOrFail($eventFiltersDTO->getLeagueId()) : null;

        $betRegion = $this->betRegionService->findOrFailBy(['name' => $outcomeFiltersDTO->getBetRegion()]);

        $priceFormat = PriceFormat::from($outcomeFiltersDTO->getPriceFormat());

        $this->leagueOddsImportSyncService->synchronizeLeagueOddsData($league, $betRegion);

        $dateWindow = $this->dateService->calculateDateWindow(
            $eventFiltersDTO->getDate() !== null
            ? DateFilterKeyword::from($eventFiltersDTO->getDate())
            : null
        );

        $paginatedEvents = $this->getPaginatedEvents(
            $eventFiltersDTO->getSportId(),
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
    /**
     * @throws NotFoundHttpException If the bet region is not found.
     * @throws ImportFailedException If the odds data import fails during synchronization.
     */
    public function getEventBestOutcomes(int $eventId, OutcomeFiltersDTO $outcomeFiltersDTO): OutcomeListResponseDTO
    {
        /** @var Event $event */
        $event = $this->findOrFail($eventId);

        $betRegion = $this->betRegionService->findOrFailBy(['name' => $outcomeFiltersDTO->getBetRegion()]);
        $priceFormat = PriceFormat::from($outcomeFiltersDTO->getPriceFormat());

        $syncStatus = $this->eventOddsImportSyncService->synchronizeEventOddsData($event, $betRegion);

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
        return new OutcomeListResponseDTO(
            $bestOutcomes,
            $syncStatus
        );
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



    private function getPaginatedEvents(
        ?int $sportId,
        ?int $leagueId,
        ?string $nameFilter,
        ?DateTimeImmutable $filterStartDate,
        ?DateTimeImmutable $filterEndDate,
        PaginationDTO $paginationDTO
    ): Pagerfanta {
        $qb = $this->eventRepository->findByFiltersQueryBuilder(
            $sportId,
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

}
