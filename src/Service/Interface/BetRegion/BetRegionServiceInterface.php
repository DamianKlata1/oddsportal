<?php

namespace App\Service\Interface\BetRegion;

use App\Entity\Interface\BetRegionInterface;
use App\Service\Interface\Import\ImportResultInterface;
use App\Service\Interface\Entity\EntityServiceInterface;

/**
 * @extends EntityServiceInterface<BetRegionInterface>
 */
interface BetRegionServiceInterface extends EntityServiceInterface
{
    public function loadBetRegions(array $betRegions): ImportResultInterface;
    public function loadBetRegion(string $betRegionName): BetRegionInterface;

}
