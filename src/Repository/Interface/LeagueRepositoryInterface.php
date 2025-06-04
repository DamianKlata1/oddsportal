<?php

namespace App\Repository\Interface;

use App\Entity\League;

/**
 * @extends RepositoryInterface<League>
 */
interface LeagueRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{
    // public function findOrCreateForRegion(string $name, int $regionId): League;
}