<?php

namespace App\ExternalApi\Interface\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Service\Interface\Import\ImportResultInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;

interface OddsApiSportsDataImporterInterface
{
    /**
     * Imports sports data from an external API.
     *
     * @param OddsApiSportsDataDTO[] $sportsDataDtos The sports data to import.
     */
    public function import(array $sportsDataDtos): ImportResultInterface;

}
