<?php

namespace App\ExternalApi\Interface\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;

interface OddsApiRegionResolverInterface
{
    public function resolveRegionName (OddsApiSportsDataDTO $sportsDataDTO): string;
}
