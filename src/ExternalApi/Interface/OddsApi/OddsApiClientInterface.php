<?php

namespace App\ExternalApi\Interface\OddsApi;

interface OddsApiClientInterface
{
    public function fetchSportsData(): array;
}