<?php

namespace App\Service\Interface\Import;

use App\Entity\League;
use App\Entity\BetRegion;

interface OddsDataImportSyncManagerInterface
{
    private const SYNC_THRESHOLD_MINUTES = 5;
    public function isSyncRequired(League $league, BetRegion $betRegion, int $thresholdInMinutes = self::SYNC_THRESHOLD_MINUTES): bool;
    public function updateSyncStatus(League $league, BetRegion $betRegion): void;
}
