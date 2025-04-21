<?php

namespace App\ExternalApi\OddsApi\Interface;

interface OddsApiClientInterface
{
    public function fetchSportsData(): array;
}