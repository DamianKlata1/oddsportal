<?php

namespace App\ExternalApi\OddsApi;

use App\Entity\Event;
use App\Entity\League;
use App\Entity\Outcome;
use App\Entity\BetRegion;
use App\Entity\Bookmaker;
use App\ExternalApi\OddsApi\Helper\ImportResult;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Service\Interface\Import\ImportResultInterface;
use App\Repository\Interface\BookmakerRepositoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;
use App\Repository\Interface\EventRepositoryInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;

class OddsApiOddsDataImporter implements OddsApiOddsDataImporterInterface
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly BookmakerRepositoryInterface $bookmakerRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly OutcomeRepositoryInterface $outcomeRepository
    ) {
    }

    public function import(array $eventsData, League $league, BetRegion $betRegion): ImportResultInterface
    {
        try {
            $this->eventRepository->startTransaction();

            foreach ($eventsData as $eventData) {
                $event = $this->importEvent(
                    $league,
                    $eventData['id'],
                    $eventData['home_team'],
                    $eventData['away_team'],
                    $eventData['commence_time']
                );
                foreach ($eventData['bookmakers'] as $bookmakerData) {
                    
                    $bookmaker = $this->importBookmaker($bookmakerData['title'], $betRegion);
                    foreach ($bookmakerData['markets'] as $marketData) {
                        foreach ($marketData['outcomes'] as $outcomeData) {
                            
                            $outcome = $this->importOutcome(
                                $outcomeData['name'],
                                $outcomeData['price'],
                                $event,
                                $marketData['key'],
                                $bookmaker,
                                $bookmakerData['last_update']
                            );
                        }
                    }
                }
            }
            $this->eventRepository->flush();
            
            $this->eventRepository->commitTransaction();
            return ImportResult::success(
                [
                    'message' => 'Data imported successfully',
                    'events' => $eventsData,
                ]
            );
        } catch (\Exception $e) {
            $this->eventRepository->rollbackTransaction();
            return ImportResult::failure(
                $e->getMessage()
            );
        }

    }
    private function importEvent(League $league, string $apiId, string $homeTeam, string $awayTeam, string $commenceTime): Event
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
        $bookmaker = $this->bookmakerRepository->findOneBy(['name' => $bookmakerName]);
        if ($bookmaker === null) {
            $bookmaker = new Bookmaker();
            $bookmaker->setName($bookmakerName);
            $bookmaker->addBetRegion($betRegion);
            //true so i dont get unique entity exception
            $this->bookmakerRepository->save($bookmaker,true);
        }
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
            $outcome->setEvent($event);
            $outcome->setLastUpdate(new \DateTimeImmutable($lastUpdate));

            $this->outcomeRepository->save($outcome);

        } else {
            $incomingDate = new \DateTimeImmutable($lastUpdate);
            $shouldUpdate = false;

            if ($outcome->getPrice() !== $price) {
                $outcome->setPrice($price);
                $shouldUpdate = true;
            }

            if ($outcome->getLastUpdate() === null || $outcome->getLastUpdate()->getTimestamp() !== $incomingDate->getTimestamp()) {
                $outcome->setLastUpdate($incomingDate);
                $shouldUpdate = true;
            }

            if ($shouldUpdate) {
                $this->outcomeRepository->save($outcome);
            }
        }
        return $outcome;
    }
}
