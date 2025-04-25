<?php

namespace App\Service\Interface\Import;

use App\DTO\Interface\SportsDataDTOInterface;
use App\Service\Interface\Import\ImportResultInterface;

interface SportsDataImporterInterface
{
    /**
     * Imports sports data from an external API.
     *
     * @param SportsDataDTOInterface[] $sportsDataDtos The sports data to import.
     */
    public function import(array $sportsDataDtos): ImportResultInterface;
}