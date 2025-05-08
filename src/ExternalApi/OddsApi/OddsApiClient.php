<?php

namespace App\ExternalApi\OddsApi;

use App\Exception\FetchFailedException;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

}