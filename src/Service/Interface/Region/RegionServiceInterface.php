<?php

namespace App\Service\Interface\Region;

use App\DTO\Region\RegionWithLeaguesDTO;
use App\Entity\Interface\RegionInterface;
use App\Service\Interface\Entity\EntityServiceInterface;
/**
 * @extends EntityServiceInterface<RegionInterface>
 */
interface RegionServiceInterface extends EntityServiceInterface
{
    /**
     *
     * @return RegionWithLeaguesDTO[]
     */
    public function getRegionsWithActiveLeagues(int $sportId): array;


}
