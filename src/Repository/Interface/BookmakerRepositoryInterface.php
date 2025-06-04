<?php

namespace App\Repository\Interface;

use App\Entity\Bookmaker;
use App\Repository\Interface\RepositoryInterface;
/**
 * @extends RepositoryInterface<Bookmaker>
 */
interface BookmakerRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{

}
