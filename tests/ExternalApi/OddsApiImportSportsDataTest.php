<?php

namespace App\Tests\ExternalApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;
use User;

class OddsApiImportSportsDataTest extends DatabaseDependantTestCase
{
    protected SportsDataImporterInterface $sportsDataImporter;
    protected OddsApiClientInterface $oddsApiClient;
    protected SportsDataDTOFactoryInterface $sportsDataDTOFactory;
    public function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->sportsDataImporter = $container->get(SportsDataImporterInterface::class);
        $this->oddsApiClient = $container->get(OddsApiClientInterface::class);
        $this->sportsDataDTOFactory = $container->get(SportsDataDTOFactoryInterface::class);

    } 
    public function testSportDataIsImportedCorrectly(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                'key',
                'group',
                'title',
                'description',
                true,
                false
            )
        ];

        $importResult = $this->sportsDataImporter->import($sportDataArray);
        
        $this->assertTrue($importResult->isSuccess());
    }
    public function testIncorrectSportDataIsNotImported(): void
    {
        $sportDataArray = [
            new OddsApiSportsDataDTO(
                '',
                'group',
                'title',
                'description',
                true,
                false
            )
        ];

        $importResult = $this->sportsDataImporter->import($sportDataArray);
        
        $this->assertFalse($importResult->isSuccess());
    }

}
