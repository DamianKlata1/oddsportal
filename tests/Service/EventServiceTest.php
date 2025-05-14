<?php

namespace App\Tests\Service;

use App\DTO\Outcome\OutcomeDTO;
use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Enum\PriceFormat;
use App\Factory\EventFactory;
use App\Factory\LeagueFactory;
use App\Factory\OutcomeFactory;
use App\Factory\BetRegionFactory;
use App\Factory\BookmakerFactory;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\Factory\OddsDataImportSyncFactory;
use App\Repository\Interface\EventRepositoryInterface;
use App\Repository\Interface\OddsDataImportSyncRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;

class EventServiceTest extends DatabaseDependantTestCase
{
    protected EventServiceInterface $eventService;
    protected LeagueFactory $leagueFactory;
    protected BetRegionFactory $betRegionFactory;
    protected BookmakerFactory $bookmakerFactory;
    protected OutcomeFactory $outcomeFactory;
    protected EventFactory $eventFactory;
    protected OutcomeRepositoryInterface $outcomeRepository;
    protected OddsDataImportSyncFactory $oddsDataImportSyncFactory;
    public function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->eventService = $container->get(EventServiceInterface::class);
        $this->leagueFactory = $container->get(LeagueFactory::class);
        $this->betRegionFactory = $container->get(BetRegionFactory::class);
        $this->bookmakerFactory = $container->get(BookmakerFactory::class);
        $this->outcomeFactory = $container->get(OutcomeFactory::class);
        $this->eventFactory = $container->get(EventFactory::class);
        $this->outcomeRepository = $container->get(OutcomeRepositoryInterface::class);
        $this->oddsDataImportSyncFactory = $container->get(OddsDataImportSyncFactory::class);
        
    }
    public function testGetEventsForLeagueReturnsEventsWithBestOutcomesForGivenLeague(): void
    {
        $league = $this->leagueFactory->createOne();
        $betRegion = $this->betRegionFactory->createOne();

        $oddsDataImportSync = $this->oddsDataImportSyncFactory->createOne([
            'league' => $league,
            'betRegion' => $betRegion,
            'lastImportedAt' => (new \DateTimeImmutable())->modify('-4 minutes'),
        ]);

        $bookmaker1 = $this->createBookmakerWithRegion('Bookmaker 1', $betRegion);
        $bookmaker2 = $this->createBookmakerWithRegion('Bookmaker 2', $betRegion);

        $event = $this->eventFactory->createOne([
            'homeTeam' => 'Home',
            'awayTeam' => 'Away',
            'commenceTime' => new \DateTimeImmutable(),
            'league' => $league,
            'apiId' => 'event_api_id'
        ]);

        // Outcome'y z dwoma bukmacherami, do jednego eventu
        $outcomesData = [
            ['Home', 1.5, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Draw', 2.0, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Away', 1.8, $bookmaker1, (new \DateTimeImmutable())->modify('+1 day')],
            ['Home', 1.6, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
            ['Draw', 2.1, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
            ['Away', 1.9, $bookmaker2, (new \DateTimeImmutable())->modify('+1 day')],
        ];

        foreach ($outcomesData as [$name, $price, $bookmaker, $lastUpdate]) {
            $this->outcomeFactory->createOne([
                'name' => $name,
                'price' => $price,
                'bookmaker' => $bookmaker,
                'market' => MarketType::H2H->toString(),
                'event' => $event,
                'lastUpdate' => $lastUpdate,
            ]);
        }

        $outcomeFiltersDTO = new OutcomeFiltersDTO(
            MarketType::H2H->toString(),
            $betRegion->getName(),
            PriceFormat::DECIMAL->toString()
        );

        $eventDTOs = $this->eventService->getEventsForLeague($league->getId(), $outcomeFiltersDTO);


        $this->assertNotEmpty($eventDTOs);
        $this->assertCount(1, $eventDTOs);

        foreach ($eventDTOs as $eventDTO) {
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

    }
    private function createBookmakerWithRegion(string $name, $betRegion)
    {
        return $this->bookmakerFactory->createOne([
            'name' => $name,
            'betRegions' => [$betRegion],
        ]);
    }
}
