<?php

namespace App\Tests\ExternalApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;

class OddsApiImportSportsDataTest extends DatabaseDependantTestCase
{
    protected SportsDataImporterInterface $sportsDataImporter;
    protected OddsApiClientInterface $oddsApiClient;
    protected SportsDataDTOFactoryInterface $sportsDataDTOFactory;
    protected SportRepositoryInterface $sportRepository;
    protected RegionRepositoryInterface $regionRepository;
    protected LeagueRepositoryInterface $leagueRepository;
    public function setUp(): void
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
        $region = $this->regionRepository->findOneBy(['name' => 'Default']);
        $league = $this->leagueRepository->findOneBy(['name' => 'league name']);

        $this->assertTrue($importResult->isSuccess());
        $this->assertNotNull($sport);
        $this->assertNotNull($region);
        $this->assertNotNull($league);
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
