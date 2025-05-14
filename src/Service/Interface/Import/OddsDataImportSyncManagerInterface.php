<?php

namespace App\Service\Interface\Import;

use App\Entity\League;
use App\Entity\BetRegion;

interface OddsDataImportSyncManagerInterface
{
    public function isSyncRequired(League $league, BetRegion $betRegion, int $thresholdInMinutes): bool;
    public function updateSyncStatus(League $league, BetRegion $betRegion): void;
}
