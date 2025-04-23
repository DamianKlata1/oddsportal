<?php

namespace App\Repository\Interface;

use App\Entity\League;

interface LeagueRepositoryInterface extends TransactionalRepositoryInterface
{
    public function save(League $league,bool $flush = false): League;
    public function findOrCreateForRegion(string $name, int $regionId): League;

}