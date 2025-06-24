<?php

namespace App\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiEventDTO;
use App\Entity\Event;
use App\Entity\League;
use App\Entity\Outcome;
use App\Entity\BetRegion;
use App\Entity\Bookmaker;
use App\ExternalApi\OddsApi\Helper\ImportResult;
use App\Repository\Interface\EventRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Service\Interface\Import\ImportResultInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;
use App\Repository\Interface\BookmakerRepositoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;

class OddsApiOddsDataImporter implements OddsApiOddsDataImporterInterface
{
    private array $localBookmakerCache = [];
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly BookmakerRepositoryInterface $bookmakerRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly OutcomeRepositoryInterface $outcomeRepository
    ) {
    }
    public function importFromList(array $eventsDTOs, League $league, BetRegion $betRegion): ImportResultInterface
    {
        $this->localBookmakerCache = [];
        $imported = [];

        $this->eventRepository->startTransaction();
        try {
            foreach ($eventsDTOs as $eventDTO) {
                $event = $this->processSingleEventData($eventDTO, $league, $betRegion);
                $imported[] = [
                    'eventId' => $event->getId(),
                    'homeTeam' => $event->getHomeTeam(),
                    'awayTeam' => $event->getAwayTeam(),
                ];
            }
            $this->eventRepository->flush();
            $this->eventRepository->commitTransaction();

            return ImportResult::success(['imported' => $imported]);
        } catch (\Exception $e) {
            $this->eventRepository->rollbackTransaction();
            return ImportResult::failure($e->getMessage());
        }
    }
    public function importSingle(OddsApiEventDTO $eventDTO, League $league, BetRegion $betRegion): ImportResultInterface
    {
        $this->eventRepository->startTransaction();
        try {
            $event = $this->processSingleEventData($eventDTO, $league, $betRegion);

            $this->eventRepository->flush();
            $this->eventRepository->commitTransaction();
            return ImportResult::success(
                [
                    'eventId' => $event->getId(),
                    'homeTeam' => $event->getHomeTeam(),
                    'awayTeam' => $event->getAwayTeam(),
                ]
            );
        } catch (\Exception $e) {
            $this->eventRepository->rollbackTransaction();
            return ImportResult::failure(
                $e->getMessage()
            );
        }

    }
    private function processSingleEventData(OddsApiEventDTO $eventDTO, League $league, BetRegion $betRegion): Event
    {
        $event = $this->importEvent(
            $league,
            $eventDTO->getId(),
            $eventDTO->getHomeTeam(),
            $eventDTO->getAwayTeam(),
            $eventDTO->getCommenceTime(),
        );
        foreach ($eventDTO->getBookmakers() as $bookmakerDTO) {
            $bookmaker = $this->importBookmaker($bookmakerDTO->getTitle(), $betRegion);
            foreach ($bookmakerDTO->getMarkets() as $marketDTO) {
                foreach ($marketDTO->getOutcomes() as $outcomeDTO) {
                    $this->importOutcome(
                        $outcomeDTO->getName(),
                        $outcomeDTO->getPrice(),
                        $event,
                        $marketDTO->getKey(),
                        $bookmaker,
                        $marketDTO->getLastUpdate()
                    );
                }
            }
        }
        return $event;
    }
    private function importEvent(League $league, string $apiId, ?string $homeTeam, ?string $awayTeam, string $commenceTime): Event
    {
        $event = $this->eventRepository->findOneBy(['apiId' => $apiId]);
        if ($event === null) {
            $event = new Event();
            $event->setApiId($apiId);
            $event->setHomeTeam($homeTeam);
            $event->setAwayTeam($awayTeam);
            $event->setCommenceTime(new \DateTimeImmutable($commenceTime));
            $event->setLeague($league);

            $this->eventRepository->save($event);
        }
        return $event;
    }
    private function importBookmaker(string $bookmakerName, BetRegion $betRegion): Bookmaker
    {
        if (isset($this->localBookmakerCache[$bookmakerName])) {
            return $this->localBookmakerCache[$bookmakerName];
        }
        $bookmaker = $this->bookmakerRepository->findOneBy(['name' => $bookmakerName]);
        if ($bookmaker === null) {
            $bookmaker = new Bookmaker();
            $bookmaker->setName($bookmakerName);
            $bookmaker->addBetRegion($betRegion);
            $this->bookmakerRepository->save($bookmaker, false);
        }

        $this->localBookmakerCache[$bookmakerName] = $bookmaker;

        return $bookmaker;
    }
    private function importOutcome(
        string $name,
        string $price,
        Event $event,
        string $market,
        Bookmaker $bookmaker,
        string $lastUpdate
    ): Outcome {
        $outcome = $this->outcomeRepository->findOneBy([
            'name' => $name,
            'event' => $event,
            'market' => $market,
            'bookmaker' => $bookmaker
        ]);
        if ($outcome === null) {
            $outcome = new Outcome();
            $outcome->setName($name);
            $outcome->setPrice($price);
            $outcome->setMarket($market);
            $outcome->setBookmaker($bookmaker);

            $event->addOutcome($outcome);
            $outcome->setLastUpdate(new \DateTimeImmutable($lastUpdate));
            $this->outcomeRepository->save($outcome);

        } else {
            $incomingDate = new \DateTimeImmutable($lastUpdate);
            if ($outcome->getPrice() !== $price) {
                $outcome->setPrice($price);
            }
            if ($outcome->getLastUpdate() === null || $outcome->getLastUpdate()->getTimestamp() !== $incomingDate->getTimestamp()) {
                $outcome->setLastUpdate($incomingDate);
            }
        }
        return $outcome;
    }
}
