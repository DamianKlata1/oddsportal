<?php

namespace App\ExternalApi\Interface\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;

interface OddsApiRegionResolverInterface
{
    public function resolve(OddsApiSportsDataDTO $sportsDataDTO): string;
}
