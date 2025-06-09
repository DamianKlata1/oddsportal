<?php

namespace App\Factory\Interface;

use App\Entity\League;
use App\DTO\League\LeagueDTO;

interface LeagueDTOFactoryInterface
{
    public function createFromEntity(League $league): LeagueDTO;

}
