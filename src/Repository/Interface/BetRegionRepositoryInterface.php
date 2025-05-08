<?php

namespace App\Repository\Interface;

use App\Entity\BetRegion;
use Doctrine\Persistence\ObjectRepository;

interface BetRegionRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(BetRegion $betRegion, bool $flush = false): BetRegion;

}
