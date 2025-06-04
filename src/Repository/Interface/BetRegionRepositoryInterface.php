<?php

namespace App\Repository\Interface;

use App\Entity\BetRegion;
use App\Repository\Interface\RepositoryInterface;

/**
 * @extends RepositoryInterface<BetRegion>
 */
interface BetRegionRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{
 
}
