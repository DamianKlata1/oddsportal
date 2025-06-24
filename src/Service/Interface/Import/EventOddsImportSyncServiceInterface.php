<?php

namespace App\Service\Interface\Import;

use App\Entity\Event;
use App\Entity\BetRegion;
use App\DTO\Sync\SyncStatusDTO;

interface EventOddsImportSyncServiceInterface
{
    public function synchronizeEventOddsData(Event $event, BetRegion $betRegion): SyncStatusDTO;
}
