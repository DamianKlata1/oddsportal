<?php

namespace App\Tests\Api;

use App\Entity\League;
use App\Enum\MarketType;
use App\Entity\Bookmaker;
use App\Enum\PriceFormat;
use App\Factory\EventFactory;
use App\Factory\LeagueFactory;
use App\Factory\OutcomeFactory;
use App\Factory\BetRegionFactory;
use App\Factory\BookmakerFactory;
use App\DTO\Outcome\OutcomeFiltersDTO;
use Zenstruck\Foundry\Persistence\Proxy;
use App\Factory\OddsDataImportSyncFactory;
use App\Repository\Interface\EventRepositoryInterface;
use App\Tests\Base\ApiTest\ApiTestBaseCase;
use App\Service\Interface\Event\EventServiceInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;

class EventTest extends ApiTestBaseCase
{
    protected EventServiceInterface $eventService;
    protected EventRepositoryInterface $eventRepository;
    protected OutcomeRepositoryInterface $outcomeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->eventService = $container->get(EventServiceInterface::class);
        $this->eventRepository = $container->get(EventRepositoryInterface::class);
        $this->outcomeRepository = $container->get(OutcomeRepositoryInterface::class);

    }
    public function testGetEventsForLeagueReturnsEventsWithBestOutcomesForGivenLeague(): void
    {
        $league = LeagueFactory::createOne();
        $betRegion = BetRegionFactory::createOne();

        $oddsDataImportSync = OddsDataImportSyncFactory::createOne([
            'league' => $league,
            'betRegion' => $betRegion,
            'lastImportedAt' => (new \DateTimeImmutable())->modify('-4 minutes'),
        ]);

        $bookmaker1 = $this->createBookmakerWithRegion('Bookmaker 1', $betRegion);
        $bookmaker2 = $this->createBookmakerWithRegion('Bookmaker 2', $betRegion);

        $event = EventFactory::createOne([
            'homeTeam' => 'Home',
            'awayTeam' => 'Away',
            'commenceTime' => (new \DateTimeImmutable())->modify('+1 day'),
            'league' => $league,
            'apiId' => 'event_api_id'
        ]);

        $outcomesData = [
            ['Home', 1.5, $bookmaker1, (new \DateTimeImmutable())],
            ['Draw', 2.0, $bookmaker1, (new \DateTimeImmutable())],
            ['Away', 1.8, $bookmaker1, (new \DateTimeImmutable())],
            ['Home', 1.6, $bookmaker2, (new \DateTimeImmutable())],
            ['Draw', 2.1, $bookmaker2, (new \DateTimeImmutable())],
            ['Away', 1.9, $bookmaker2, (new \DateTimeImmutable())],
        ];

        foreach ($outcomesData as [$name, $price, $bookmaker, $lastUpdate]) {
            OutcomeFactory::createOne([
                'name' => $name,
                'price' => $price,
                'bookmaker' => $bookmaker,
                'market' => MarketType::H2H->toString(),
                'event' => $event,
                'lastUpdate' => $lastUpdate,
            ]);
        }
        $this->assertNotNull($this->eventRepository->find($event->getId()));
        $this->client->request('GET', '/api/events/league/' . $league->getId(), [
            'market' => MarketType::H2H->toString(),
            'betRegion' => $betRegion->getName(),
            'priceFormat' => PriceFormat::DECIMAL->toString(),
        ]);


        $responseData = json_decode($this->client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        foreach ($responseData->events as $event) {
            $this->assertSame('Home', $event->homeTeam);
            $this->assertSame('Away', $event->awayTeam);
            $this->assertNotEmpty($event->bestOutcomes);

            $this->assertCount(3, $event->bestOutcomes);
            foreach ($event->bestOutcomes as $outcome) {
                match ($outcome->name) {
                    'Home' => $this->assertSame('1.6', $outcome->price),
                    'Draw' => $this->assertSame('2.1', $outcome->price),
                    'Away' => $this->assertSame('1.9', $outcome->price),
                    default => $this->fail("Unexpected outcome name: " . $outcome->name)
                };
            }
        }
        $this->assertSame(1, $responseData->pagination->page);
        $this->assertSame(10, $responseData->pagination->limit);



    }
    private function createBookmakerWithRegion(string $name, $betRegion): Bookmaker|Proxy
    {
        return BookmakerFactory::createOne([
            'name' => $name,
            'betRegions' => [$betRegion],
        ]);
    }

}
