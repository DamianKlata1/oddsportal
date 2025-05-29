<?php

namespace App\Service\Import;

use App\Entity\League;
use App\Entity\BetRegion;
use App\Entity\OddsDataImportSync;
use App\Repository\Interface\OddsDataImportSyncRepositoryInterface;
use App\Service\Interface\Import\OddsDataImportSyncManagerInterface;

class OddsDataImportSyncManager implements OddsDataImportSyncManagerInterface
{

    private const SYNC_THRESHOLD_SECONDS = 3600; // 1 hour
    public function __construct(
        private readonly OddsDataImportSyncRepositoryInterface $oddsDataImportSyncRepository,
    ) {
    }

    public function isSyncRequired(League $league, BetRegion $betRegion): bool
    {
 
        $status = $this->oddsDataImportSyncRepository->findOneBy(['league' => $league, 'betRegion' => $betRegion]);
        if (!$status) {
            return true; // no status =  first import
        }
        $now = new \DateTimeImmutable();
        return $status->getLastImportedAt()->modify(sprintf('+%d seconds', self::SYNC_THRESHOLD_SECONDS)) < $now;
    }

    public function updateSyncStatus(League $league, BetRegion $betRegion): void
    {
        $now = new \DateTimeImmutable();
        $status = $this->oddsDataImportSyncRepository->findOneBy(['league' => $league, 'betRegion' => $betRegion]);

        if (!$status) {
            $status = new OddsDataImportSync();
            $status->setLeague($league);
            $status->setBetRegion($betRegion);
        }
        $status->setLastImportedAt($now);

        $this->oddsDataImportSyncRepository->save($status, true);
    }

}
