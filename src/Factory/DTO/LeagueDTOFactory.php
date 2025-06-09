<?php

namespace App\Factory\DTO;

use App\Entity\League;
use App\DTO\League\LeagueDTO;
use App\Factory\Interface\LeagueDTOFactoryInterface;

class LeagueDTOFactory implements LeagueDTOFactoryInterface
{
    public function createFromEntity(League $league): LeagueDTO
    {
        return new LeagueDTO($league->getId(), $league->getName(),$league->getLogoPath());
    }
}
