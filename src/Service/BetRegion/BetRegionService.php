<?php

namespace App\Service\BetRegion;

use App\Entity\BetRegion;
use App\Entity\Interface\BetRegionInterface;
use App\Exception\ResourceNotFoundException;
use App\ExternalApi\OddsApi\Helper\ImportResult;
use App\Service\Entity\AbstractEntityService;
use App\Service\Interface\Import\ImportResultInterface;
use App\Repository\Interface\BetRegionRepositoryInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;
use App\Service\Interface\RegionResolver\RegionResolverInterface;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;

class BetRegionService extends AbstractEntityService implements BetRegionServiceInterface
{
    public function __construct(
        private readonly BetRegionRepositoryInterface $betRegionRepository,
        private readonly RegionResolverInterface $regionResolver,
        private readonly RegionLogoPathResolverInterface $logoPathResolver,
    ) {
        parent::__construct($betRegionRepository);
    }

    public function loadBetRegions(array $betRegions): ImportResultInterface
    {
        try {
            $importedBetRegions = [];
            foreach ($betRegions as $betRegionName) {
                $this->loadBetRegion($betRegionName);
                $importedBetRegions[] = $betRegionName;
            }
            return ImportResult::success([
                'betRegions' => $importedBetRegions,
            ]);
        } catch (\Exception $e) {
            return ImportResult::failure('Failed to load bet regions: ' . $e->getMessage());
        }
    }
    public function loadBetRegion(string $betRegionName): BetRegion
    {
        $betRegion = $this->betRegionRepository->findOneBy(['name' => $betRegionName]);
        if (!$betRegion) {
            $betRegion = new BetRegion();
            $betRegion->setName($betRegionName);

            $regionResolved = $this->regionResolver->resolve($betRegionName);
        
            $betRegion->setLogoPath($this->logoPathResolver->resolve($regionResolved));
            $this->betRegionRepository->save($betRegion, true);
        }
        return $betRegion;
    }
}
