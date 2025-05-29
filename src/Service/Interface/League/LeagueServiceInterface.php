<?php

namespace App\Service\Interface\League;

use App\Service\Interface\Helper\DeleteResultInterface;

interface LeagueServiceInterface
{
    public function removeOutdatedLeagues(array $sportsDataDTOs): DeleteResultInterface;

}
