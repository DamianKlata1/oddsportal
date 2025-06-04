<?php

namespace App\Repository\Interface;

use App\Entity\Sport;
/**
 * @extends RepositoryInterface<Sport>
 */
interface SportRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{
    // public function findOrCreate(string $name): Sport;

}