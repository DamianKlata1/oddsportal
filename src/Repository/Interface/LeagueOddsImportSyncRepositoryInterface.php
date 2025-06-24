<?php

namespace App\Repository\Interface;

use App\Entity\LeagueOddsImportSync;
use App\Repository\Interface\RepositoryInterface;

/**
 * @extends RepositoryInterface<LeagueOddsImportSync>
 */
interface LeagueOddsImportSyncRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{

}
