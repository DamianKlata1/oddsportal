<?php

namespace App\ExternalApi\Interface\OddsApi;

interface OddsApiClientInterface
{
    public function fetchSportsData(): array;
    public function fetchOddsDataForLeague(string $leagueKey, string $region): array;
}