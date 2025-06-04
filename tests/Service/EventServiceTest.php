<?php

namespace App\Tests\Service;

use App\DTO\Event\EventFiltersDTO;
use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Entity\Bookmaker;
use App\Enum\PriceFormat;
use App\Factory\EventFactory;
use App\Factory\LeagueFactory;
use App\DTO\Outcome\OutcomeDTO;
use App\Factory\OutcomeFactory;
use App\Factory\BetRegionFactory;
use App\Factory\BookmakerFactory;
use App\DTO\Pagination\PaginationDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use Zenstruck\Foundry\Persistence\Proxy;
use App\Factory\OddsDataImportSyncFactory;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;
use App\Repository\Interface\OddsDataImportSyncRepositoryInterface;

class EventServiceTest extends DatabaseDependantTestCase
{
    protected EventServiceInterface $eventService;    
    protected OutcomeRepositoryInterface $outcomeRepository;
    public function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->eventService = $container->get(EventServiceInterface::class);
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
            ['Home', 1.5, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Draw', 2.0, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Away', 1.8, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Home', 1.6, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
            ['Draw', 2.1, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
            ['Away', 1.9, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
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

        $outcomeFiltersDTO = new OutcomeFiltersDTO(
            $betRegion->getName(),
            PriceFormat::DECIMAL->toString()
        );

        $eventListDTO = $this->eventService->getEvents(
            new EventFiltersDTO(
                leagueId: $league->getId(),
            ),
            $outcomeFiltersDTO,
            new PaginationDTO(1,10)
        );


        $this->assertNotEmpty($eventListDTO->getEvents());

        foreach ($eventListDTO->getEvents() as $eventDTO) {
            
            $this->assertSame('Home', $eventDTO->getHomeTeam());
            $this->assertSame('Away', $eventDTO->getAwayTeam());
            $this->assertNotEmpty($eventDTO->getBestOutcomes());
            $bestOutcomes = $eventDTO->getBestOutcomes();
            $this->assertCount(3, $bestOutcomes);
            /** @var OutcomeDTO $outcome */
            foreach ($bestOutcomes as $outcome) {
                match ($outcome->getName()) {
                    'Home' => $this->assertSame('1.6', $outcome->getPrice()),
                    'Draw' => $this->assertSame('2.1', $outcome->getPrice()),
                    'Away' => $this->assertSame('1.9', $outcome->getPrice()),
                    default => $this->fail("Unexpected outcome name: " . $outcome->getName())
                };
            }
        }
        $this->assertSame(1, $eventListDTO->getPagination()->getPage());
        $this->assertSame(10, $eventListDTO->getPagination()->getLimit());

    }
    private function createBookmakerWithRegion(string $name, $betRegion): Bookmaker|Proxy
    {
        return BookmakerFactory::createOne([
            'name' => $name,
            'betRegions' => [$betRegion],
        ]);
    }
}
