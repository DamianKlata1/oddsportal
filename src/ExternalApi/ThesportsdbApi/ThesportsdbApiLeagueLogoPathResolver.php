<?php

namespace App\ExternalApi\ThesportsdbApi;

use App\ExternalAPI\ThesportsdbApi\Interface\LeagueLogoPathResolverInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ThesportsdbApiLeagueLogoPathResolver implements LeagueLogoPathResolverInterface
{
    public function __construct(
        private readonly string              $thesportsdbApiUrl,
        private readonly string              $thesportsdbApiKey,
        private readonly HttpClientInterface $client,
    )
    {
    }

    public function resolve(string $name): string
    {
        $leagueId = $this->normalizeName($name);
        $url = "{$this->thesportsdbApiUrl}/{$this->thesportsdbApiKey}/lookupleague.php?id={$leagueId}";
        return $url;
    }
    private function getLeagueIdByName(string $name): string
    {
        $response = $this->client->request('GET', $this->resolve($name));
        $data = $response->toArray();
        if (isset($data['leagues'][0]['idLeague'])) {
            return $data['leagues'][0]['idLeague'];
        }
        throw new \RuntimeException("League ID not found for league name: {$name}");
    }

}