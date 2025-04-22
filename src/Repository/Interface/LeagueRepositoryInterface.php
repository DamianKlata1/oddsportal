<?php

namespace App\Repository\Interface;

use App\Entity\League;

interface LeagueRepositoryInterface
{
    public function save(League $league): League;
    public function findOrCreateForRegion(string $name, int $regionId): League;

}