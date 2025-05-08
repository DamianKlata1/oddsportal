<?php

namespace App\Service\Interface\BetRegion;

use App\Entity\BetRegion;
use App\Service\Interface\Import\ImportResultInterface;

interface BetRegionServiceInterface
{
    public function loadBetRegions(array $betRegions): ImportResultInterface;
    public function loadBetRegion(string $betRegionName): BetRegion;

}
