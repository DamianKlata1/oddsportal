<?php

namespace App\Tests\ExternalApi;

use App\Entity\League;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Repository\Interface\SportRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;

class OddsApiImportSportsDataTest extends DatabaseDependantTestCase
{
    protected SportsDataImporterInterface $sportsDataImporter;
    protected OddsApiClientInterface $oddsApiClient;
    protected SportsDataDTOFactoryInterface $sportsDataDTOFactory;
    protected SportRepositoryInterface $sportRepository;
    protected RegionRepositoryInterface $regionRepository;
    protected LeagueRepositoryInterface $leagueRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->sportsDataImporter = $container->get(SportsDataImporterInterface::class);
        $this->oddsApiClient = $container->get(OddsApiClientInterface::class);
        $this->sportsDataDTOFactory = $container->get(SportsDataDTOFactoryInterface::class);
        $this->sportRepository = $container->get(SportRepositoryInterface::class);
        $this->regionRepository = $container->get(RegionRepositoryInterface::class);
        $this->leagueRepository = $container->get(LeagueRepositoryInterface::class);
    }
    public function testSportsDataIsImportedCorrectly(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name',
                'sport name',
                'league name',
                'description',
                true,
                false
            )
        ];

        $importResult = $this->sportsDataImporter->import($sportDataArray);

        $sport = $this->sportRepository->findOneBy(['name' => 'sport name']);
        $region = $this->regionRepository->findOneBy(['name' => 'World']);
        $league = $this->leagueRepository->findOneBy(['name' => 'league name']);

        $this->assertTrue($importResult->isSuccess());
        $this->assertNotNull($sport);
        $this->assertNotNull($region);
        $this->assertNotNull($league);
        $this->assertEquals('sport name', $sport->getName());
        $this->assertEquals('World', $region->getName());
        $this->assertEquals('league name', $league->getName());
        $this->assertEquals('default_region_sport_name_league_name', $league->getApiKey());
        $this->assertTrue($league->isActive());
    }
    public function testSportsDataImportWithExistingSport(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name',
                'sport name',
                'league name',
                'description',
                true,
                false
            ),
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name2',
                'sport name',
                'league name 2',
                'description',
                true,
                false
            )
        ];

        $this->sportsDataImporter->import($sportDataArray);
        $sport = $this->sportRepository->findOneBy(['name' => 'sport name']);
        $region = $this->regionRepository->findOneBy(['name' => 'World']);
        $league = $this->leagueRepository->findOneBy(['name' => 'league name']);
        $league2 = $this->leagueRepository->findOneBy(['name' => 'league name 2']);

        $this->assertNotNull($sport);
        $this->assertNotNull($region);
        $this->assertNotNull($league);
        $this->assertEquals('sport name', $sport->getName());
        $this->assertEquals('World', $region->getName());
        $this->assertEquals('league name', $league->getName());
        $this->assertEquals('default_region_sport_name_league_name', $league->getApiKey());
        $this->assertEquals('default_region_sport_name_league_name2', $league2->getApiKey());
        $this->assertEquals($region->getId(), $league->getRegion()->getId());
        $this->assertEquals($region->getId(), $league2->getRegion()->getId());
    }
    public function testSportsDataImportUpdatesApiKeyCorrectly(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name',
                'sport name',
                'league name',
                'description',
                true,
                false
            )
        ];
        $this->sportsDataImporter->import($sportDataArray);
        $league = $this->leagueRepository->findOneBy(['name' => 'league name']);
        
        $this->assertTrue($league->isActive());
        $sportDataArrayUpdated = [
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name',
                'sport name',
                'league name',
                'description',
                false,
                false
            )
        ];
        $this->sportsDataImporter->import($sportDataArrayUpdated);

        $leagueUpdated = $this->leagueRepository->findOneBy(['name' => 'league name']);

        $this->assertFalse($leagueUpdated->isActive());
    }
    public function testSportsDataImportFailsWithInvalidDto(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                'default_region_sport_name_league_name',
                'sport name',
                'league name',
                '',
                true,
                false
            )
        ];

        $importResult = $this->sportsDataImporter->import($sportDataArray);
        $sport = $this->sportRepository->findOneBy(['name' => 'sport name']);
        $region = $this->regionRepository->findOneBy(['name' => 'Default']);
        $league = $this->leagueRepository->findOneBy(['name' => 'league name']);

        $this->assertFalse($importResult->isSuccess());
        $this->assertNull($league);
        $this->assertNull($sport);
        $this->assertNull($region);
    }

}
