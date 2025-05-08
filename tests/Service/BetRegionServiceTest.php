<?php

namespace App\Tests\Service;

use App\Repository\Interface\BetRegionRepositoryInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;
use App\Tests\Base\KernelTest\DatabaseDependantTestCase;

class BetRegionServiceTest extends DatabaseDependantTestCase
{
    private BetRegionServiceInterface $betRegionService;
    private BetRegionRepositoryInterface $betRegionRepository;
    public function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->betRegionService = $container->get(BetRegionServiceInterface::class);
        $this->betRegionRepository = $container->get(BetRegionRepositoryInterface::class);
    }

    public function testRegionsAreLoadedCorrectly(): void
    {
        $betRegions = [
            'us',
            'uk',
            'us2',
            'au',
            'eu',
        ];

        $this->betRegionService->loadBetRegions($betRegions);

        $betRegion = $this->betRegionRepository->findOneBy(['name' => 'us']);
        $betRegion2 = $this->betRegionRepository->findOneBy(['name' => 'uk']);
        $betRegion3 = $this->betRegionRepository->findOneBy(['name' => 'us2']);
        $betRegion4 = $this->betRegionRepository->findOneBy(['name' => 'au']);
        $betRegion5 = $this->betRegionRepository->findOneBy(['name' => 'eu']);

        $this->assertNotNull($betRegion);
        $this->assertNotNull($betRegion2);
        $this->assertNotNull($betRegion3);
        $this->assertNotNull($betRegion4);
        $this->assertNotNull($betRegion5);
    }


}
