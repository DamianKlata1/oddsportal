<?php

namespace App\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\ExternalApi\Interface\OddsApi\OddsApiRegionResolverInterface;
use App\Service\Region\RegionResolver\AbstractRegionResolver;

class OddsApiRegionResolver extends AbstractRegionResolver implements OddsApiRegionResolverInterface
{
    public function resolve(OddsApiSportsDataDTO $sportsDataDto): string
    {
        if ($sportsDataDto->hasOutrights()) {
            return 'Outrights';
        }
        $text = implode(' ', [
            $sportsDataDto->getKey(),
            $sportsDataDto->getTitle(),
            $sportsDataDto->getGroup(),
            $sportsDataDto->getDescription()
        ]);

        return $this->matchRegion($text) ?? 'World';
    }
}
