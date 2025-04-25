<?php

namespace App\Tests\ExternalApi;

use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;

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
    public function testSportDataIsImported(): void
    {
        $sportDataArray = $this->oddsApiClient->fetchSportsData();
        $sportDataDTOs = $this->sportsDataDTOFactory->createFromArrayList($sportDataArray);

        $importResult = $this->sportsDataImporter->import($sportDataDTOs);
        
        $this->assertTrue($importResult->isSuccess());
    }
}
