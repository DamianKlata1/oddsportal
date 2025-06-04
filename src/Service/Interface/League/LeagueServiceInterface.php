<?php

namespace App\Service\Interface\League;

use App\Entity\Interface\LeagueInterface;
use App\Service\Interface\Helper\DeleteResultInterface;
use App\Service\Interface\Entity\EntityServiceInterface;

/**
 * @extends EntityServiceInterface<LeagueInterface>
 */
interface LeagueServiceInterface extends EntityServiceInterface
{
    public function removeOutdatedLeagues(array $sportsDataDTOs): DeleteResultInterface;

}
