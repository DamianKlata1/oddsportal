<?php

namespace App\Repository\Interface;

use App\Entity\OddsDataImportSync;
use App\Repository\Interface\RepositoryInterface;

/**
 * @extends RepositoryInterface<OddsDataImportSync>
 */
interface OddsDataImportSyncRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{

}
