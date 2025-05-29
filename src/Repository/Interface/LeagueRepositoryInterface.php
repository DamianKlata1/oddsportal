<?php

namespace App\Repository\Interface;

use App\Entity\League;
use Doctrine\Persistence\ObjectRepository;

interface LeagueRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(League $league,bool $flush = false): League;
    public function remove(League $league, bool $flush = false): void;

    // public function findOrCreateForRegion(string $name, int $regionId): League;

}