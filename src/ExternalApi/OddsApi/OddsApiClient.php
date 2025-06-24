<?php

namespace App\ExternalApi\OddsApi;

use App\Entity\League;
use App\Enum\MarketType;
use App\Exception\FetchFailedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;

class OddsApiClient implements OddsApiClientInterface
{
    public function __construct(
        private readonly string $oddsApiUrl,
        private readonly string $oddsApiKey,
        private readonly HttpClientInterface $client
    ) {
    }
    public function fetchSportsData(): array
    {
        try {
            $response = $this->client->request(
                'GET',
                "{$this->oddsApiUrl}?apiKey={$this->oddsApiKey}&all=true"
            );
            return $response->toArray();
        } catch (\Exception $e) {
            throw new FetchFailedException('Error fetching data from Odds API: ' . $e->getMessage(), 502);
        }
    }
    public function fetchOddsDataForLeague(string $leagueApiKey, string $betRegion, MarketType $marketType = MarketType::H2H): array
    {
        try {
            $response = $this->client->request(
                'GET',
                "{$this->oddsApiUrl}/{$leagueApiKey}/odds/?apiKey={$this->oddsApiKey}&regions={$betRegion}&markets={$marketType->value}"
            );
            return $response->toArray();
        } catch (\Exception $e) {
            throw new FetchFailedException('Error fetching data from Odds API: ' . $e->getMessage(), 502);
        }
    }
    public function fetchOddsDataForEvent(string $leagueApiKey, string $eventApiKey, string $betRegion, MarketType $marketType = MarketType::H2H): array
    {
        try {
            $response = $this->client->request(
                'GET',
                "{$this->oddsApiUrl}/{$leagueApiKey}/events/{$eventApiKey}/odds/?apiKey={$this->oddsApiKey}&regions={$betRegion}&markets={$marketType->value}"
            );
            return $response->toArray();
        } catch (\Exception $e) {
            throw new FetchFailedException('Error fetching data from Odds API: ' . $e->getMessage(), 502);
        }
    }

}