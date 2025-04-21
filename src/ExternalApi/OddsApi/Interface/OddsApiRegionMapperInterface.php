<?php

namespace App\ExternalApi\OddsApi\Interface;

use App\Entity\Sport;
use App\Entity\League;

interface OddsApiRegionMapperInterface
{
    public function mapRegionToLeague(League $league, array $sportsData): void;
    public function mapRegionsToSport(Sport $sport, array $sportsData): void;
}
