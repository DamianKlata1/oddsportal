<?php

namespace App\Tests\Service;

use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Enum\PriceFormat;
use App\Factory\EventFactory;
use App\Factory\LeagueFactory;
use App\Factory\OutcomeFactory;
use App\Factory\BetRegionFactory;
use App\Factory\BookmakerFactory;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\Repository\Interface\EventRepositoryInterface;
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

    }
    public function testGetEventsForLeague(): void
    {
        $league = $this->leagueFactory->createOne();
        $betRegion = $this->betRegionFactory->createOne();

        $bookmaker1 = $this->bookmakerFactory->createOne([
            'name' => 'Bookmaker 1',
            'betRegions' => [$betRegion],
        ]);

        $bookmaker2 = $this->bookmakerFactory->createOne([
            'name' => 'Bookmaker 2',
            'betRegions' => [$betRegion],
        ]);

        $event = $this->eventFactory->createOne([
            'homeTeam' => 'Home',
            'awayTeam' => 'Away',
            'commenceTime' => new \DateTimeImmutable(),
            'league' => $league,
        ]);

        // Outcome'y z dwoma bukmacherami, do jednego eventu
        $outcomesData = [
            ['Home', 1.5, $bookmaker1, new \DateTimeImmutable()],
            ['Draw', 2.0, $bookmaker1, new \DateTimeImmutable()],
            ['Away', 1.8, $bookmaker1, new \DateTimeImmutable()],
            ['Home', 1.6, $bookmaker2, new \DateTimeImmutable()],
            ['Draw', 2.1, $bookmaker2, new \DateTimeImmutable()],
            ['Away', 1.9, $bookmaker2, new \DateTimeImmutable()],
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
            
        }

    }
}
