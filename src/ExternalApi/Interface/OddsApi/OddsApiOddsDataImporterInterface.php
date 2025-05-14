<?php

namespace App\ExternalApi\Interface\OddsApi;


use App\Entity\League;
use App\Entity\BetRegion;
use App\Service\Interface\Import\ImportResultInterface;

interface OddsApiOddsDataImporterInterface
{
    public function import(array $eventsData, League $league, BetRegion $betRegion): ImportResultInterface;

}
