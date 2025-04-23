<?php

namespace App\Repository\Interface;

use App\Entity\Region;

interface RegionRepositoryInterface extends TransactionalRepositoryInterface
{
    public function save(Region $region, bool $flush = false): Region;
    public function findOrCreateForSport(string $name,int $sportId): Region;
    public function find($id, $lockMode = null, $lockVersion = null);
}