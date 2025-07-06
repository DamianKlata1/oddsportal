<?php

namespace App\Repository\Interface;

use App\Entity\CommandLog;
/**
 * @extends RepositoryInterface<CommandLog>
 */
interface CommandLogRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{

}
