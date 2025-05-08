<?php

namespace App\Service\Region\RegionResolver;

use App\Service\Interface\RegionResolver\RegionResolverInterface;

class RegionResolver extends AbstractRegionResolver implements RegionResolverInterface
{
    public function resolve(string $text): string
    {
        return $this->matchRegion($text) ?? 'Default';
    }

}
