<?php

namespace App\Repository\Interface;

use App\Entity\OddsDataImportSync;
use Doctrine\Persistence\ObjectRepository;

interface OddsDataImportSyncRepositoryInterface extends ObjectRepository
{
    public function save(OddsDataImportSync $entity, bool $flush = false): void;

}
