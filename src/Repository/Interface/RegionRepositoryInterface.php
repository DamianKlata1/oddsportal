<?php

namespace App\Repository\Interface;

use App\Entity\Region;

/**
 * @extends RepositoryInterface<Region>
 */
interface RegionRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{    
    /**
     * @return Region[] Returns an array of Region objects
     */
    public function findWithActiveLeaguesBySport(int $sportId): array;

    // public function findOrCreateForSport(string $name,int $sportId): Region;
}