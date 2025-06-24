<?php

namespace App\DTO\Outcome;

use App\DTO\Sync\SyncStatusDTO;

class OutcomeListResponseDTO
{
    public function __construct(
        /**
         * @var OutcomeDTO[] $outcomes
         */
        private readonly array $outcomes,
        private readonly SyncStatusDTO $syncStatus
    ) {
    }

    /**
     * @return  OutcomeDTO[]
     */
    public function getOutcomes(): array
    {
        return $this->outcomes;
    }
    
    public function getSyncStatus(): SyncStatusDTO
    {
        return $this->syncStatus;
    }
}
