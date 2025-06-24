<?php

namespace App\Service\Interface\Import;

use App\Entity\League;
use App\Entity\BetRegion;

interface LeagueOddsImportSyncServiceInterface
{
    public function synchronizeLeagueOddsData(?League $league, BetRegion $betRegion): void;

}   
