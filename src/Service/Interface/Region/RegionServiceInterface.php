<?php

namespace App\Service\Interface\Region;

use App\DTO\Region\RegionWithLeaguesDTO;

interface RegionServiceInterface
{
    /**
     *
     * @return RegionWithLeaguesDTO[]
     */
    public function getRegionsWithActiveLeagues(int $sportId): array;


}
