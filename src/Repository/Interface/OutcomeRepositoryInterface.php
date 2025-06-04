<?php

namespace App\Repository\Interface;

use App\Entity\Outcome;
use App\Repository\Interface\RepositoryInterface;
/**
 * @extends RepositoryInterface<Outcome>
 */
interface OutcomeRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{

}
