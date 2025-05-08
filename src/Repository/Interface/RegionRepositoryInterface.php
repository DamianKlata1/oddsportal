<?php

namespace App\Repository\Interface;

use App\Entity\Region;
use Doctrine\Persistence\ObjectRepository;

interface RegionRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(Region $region, bool $flush = false): Region;
    
    /**
     * @return Region[] Returns an array of Region objects
     */
    public function findWithActiveLeaguesBySport(int $sportId): array;

    // public function findOrCreateForSport(string $name,int $sportId): Region;
}