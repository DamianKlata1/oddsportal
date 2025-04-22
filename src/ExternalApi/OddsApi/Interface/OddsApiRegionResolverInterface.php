<?php

namespace App\ExternalApi\OddsApi\Interface;

use App\Entity\Sport;
use App\Entity\League;

interface OddsApiRegionResolverInterface
{
    public function resolveRegionName (array $leagueData): string;
}
