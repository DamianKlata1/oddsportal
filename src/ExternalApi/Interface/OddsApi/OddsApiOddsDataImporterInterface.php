<?php

namespace App\ExternalApi\Interface\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiEventDTO;
use App\Entity\League;
use App\Entity\BetRegion;
use App\Service\Interface\Import\ImportResultInterface;

interface OddsApiOddsDataImporterInterface
{
    /**
     * @param OddsApiEventDTO[] $eventsDTOs
     */
    public function importFromList(array $eventsDTOs, League $league, BetRegion $betRegion): ImportResultInterface;
    public function importSingle(OddsApiEventDTO $eventDTO, League $league, BetRegion $betRegion): ImportResultInterface;

}
