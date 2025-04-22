<?php

namespace App\Repository\Interface;

use App\Entity\Region;

interface RegionRepositoryInterface
{
    public function save(Region $region): Region;
    public function findOrCreateForSport(string $name,int $sportId): Region;
    public function find($id, $lockMode = null, $lockVersion = null);
}