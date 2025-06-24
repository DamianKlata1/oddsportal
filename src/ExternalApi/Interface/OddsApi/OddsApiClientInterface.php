<?php

namespace App\ExternalApi\Interface\OddsApi;

use App\Enum\MarketType;

interface OddsApiClientInterface
{
    public function fetchSportsData(): array;
    public function fetchOddsDataForLeague(string $leagueApiKey, string $region, MarketType $marketType = MarketType::H2H): array;
    public function fetchOddsDataForEvent(string $leagueApiKey, string $eventApiKey, string $region, MarketType $marketType = MarketType::H2H): array;
}