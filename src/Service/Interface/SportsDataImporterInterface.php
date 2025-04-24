<?php

namespace App\Service\Interface;

use App\ExternalApi\OddsApi\Helper\ImportResult;

interface SportsDataImporterInterface
{
    public function import(): ImportResult;
}