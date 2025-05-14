<?php

namespace App\Tests\ExternalApi\OddsApi;

use App\Factory\LeagueFactory;
use App\Factory\BetRegionFactory;
use App\ExternalApi\OddsApi\OddsApiOddsDataImporter;
use App\Factory\RegionFactory;
use App\Repository\Interface\EventRepositoryInterface;
use App\Repository\Interface\OutcomeRepositoryInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;
use App\Repository\Interface\BookmakerRepositoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;
use App\Factory\SportFactory;
use App\Repository\Interface\BetRegionRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;

class OddsApiImportOddsDataTest extends DatabaseDependantTestCase
{
    protected OddsApiOddsDataImporterInterface $oddsApiOddsDataImporter;
    protected LeagueFactory $leagueFactory;
    protected BetRegionFactory $betRegionFactory;
    protected EventRepositoryInterface $eventRepository;
    protected OutcomeRepositoryInterface $outcomeRepository;
    protected BookmakerRepositoryInterface $bookmakerRepository;
    protected LeagueRepositoryInterface $leagueRepository;
    protected BetRegionRepositoryInterface $betRegionRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->oddsApiOddsDataImporter = $container->get(OddsApiOddsDataImporterInterface::class);
        $this->leagueFactory = $container->get(LeagueFactory::class);
        $this->leagueRepository = $container->get(LeagueRepositoryInterface::class);
        $this->eventRepository = $container->get(EventRepositoryInterface::class);
        $this->outcomeRepository = $container->get(OutcomeRepositoryInterface::class);
        $this->bookmakerRepository = $container->get(BookmakerRepositoryInterface::class);
        $this->betRegionFactory = $container->get(BetRegionFactory::class);
        $this->betRegionRepository = $container->get(BetRegionRepositoryInterface::class);


    }
    public function testOddsDataIsImportedCorrectly(): void
    {
        $league = $this->leagueFactory->createOne()->_real();
        $betRegion = $this->betRegionFactory->createOne()->_real();
        $leagueFromRepository = $this->leagueRepository->findOneBy(['id' => $league->getId()]);
        $betRegionFromRepository = $this->betRegionRepository->findOneBy(['id' => $betRegion->getId()]);

        $eventsData = [
            [
                'id' => '12345',
                'home_team' => 'Team A',
                'away_team' => 'Team B',
                'commence_time' => '2023-10-01T12:00:00Z',
                'bookmakers' => [
                    [
                        'title' => 'bookmaker1',
                        'last_update' => '2023-10-01T11:00:00Z',
                        'markets' => [
                            [
                                'key' => 'h2h',
                                'outcomes' => [
                                    ['name' => 'Team A', 'price' => 1.5],
                                    ['name' => 'Team B', 'price' => 2.5],
                                    ['name' => 'Draw', 'price' => 2.7],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $importStatus = $this->oddsApiOddsDataImporter->
            import($eventsData, $leagueFromRepository, $betRegionFromRepository);

        $this->assertTrue($importStatus->isSuccess());
        $event = $this->eventRepository->findOneBy(['apiId' => '12345']);
        $bookmaker = $this->bookmakerRepository->findOneBy(['name' => 'bookmaker1']);

        $outcome1 = $this->outcomeRepository->findOneBy(['name' => 'Team A', 'event' => $event, 'bookmaker' => $bookmaker]);
        $outcome2 = $this->outcomeRepository->findOneBy(['name' => 'Team B', 'event' => $event, 'bookmaker' => $bookmaker]);
        $outcome3 = $this->outcomeRepository->findOneBy(['name' => 'Draw', 'event' => $event, 'bookmaker' => $bookmaker]);

        $this->assertNotNull($event);
        $this->assertNotNull($bookmaker);

        $this->assertNotNull($outcome1);
        $this->assertEquals('Team A', $outcome1->getName());
        $this->assertEquals(1.5, $outcome1->getPrice());
        $this->assertEquals('h2h', $outcome1->getMarket());
        $this->assertEquals($event, $outcome1->getEvent());
        $this->assertEquals($bookmaker, $outcome1->getBookmaker());
        $this->assertEquals('2023-10-01T11:00:00Z', $outcome1->getLastUpdate()->format('Y-m-d\TH:i:s\Z'));

        $this->assertNotNull($outcome2);
        $this->assertEquals('Team B', $outcome2->getName());
        $this->assertEquals(2.5, $outcome2->getPrice());
        $this->assertEquals('h2h', $outcome2->getMarket());
        $this->assertEquals($event, $outcome2->getEvent());
        $this->assertEquals($bookmaker, $outcome2->getBookmaker());
        $this->assertEquals('2023-10-01T11:00:00Z', $outcome2->getLastUpdate()->format('Y-m-d\TH:i:s\Z'));

        $this->assertNotNull($outcome3);
        $this->assertEquals('Draw', $outcome3->getName());
        $this->assertEquals(2.7, $outcome3->getPrice());
        $this->assertEquals('h2h', $outcome3->getMarket());
        $this->assertEquals($event, $outcome3->getEvent());
        $this->assertEquals($bookmaker, $outcome3->getBookmaker());
        $this->assertEquals('2023-10-01T11:00:00Z', $outcome3->getLastUpdate()->format('Y-m-d\TH:i:s\Z'));


    }

}
