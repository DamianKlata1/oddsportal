<?php

namespace App\DTO\Sync;

use DateTimeImmutable;
use App\Enum\SyncStatus;

class SyncStatusDTO
{
    public function __construct(
        public readonly SyncStatus $status,
        public readonly ?DateTimeImmutable $nextUpdateAllowedAt = null
    ) {
    }

    public function isSyncRequired(): bool
    {
        return $this->status === SyncStatus::REQUIRED;
    }

    public function getStatus(): SyncStatus
    {
        return $this->status;
    }

    public function getNextUpdateAllowedAt(): DateTimeImmutable|null
    {
        return $this->nextUpdateAllowedAt;
    }
}
