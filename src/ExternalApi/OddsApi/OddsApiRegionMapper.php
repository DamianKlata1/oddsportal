<?php

namespace App\ExternalApi\OddsApi;

use App\Entity\Sport;
use App\Entity\League;
use App\Entity\Region;
use App\ExternalApi\OddsApi\Interface\OddsApiRegionMapperInterface;
use App\ExternalApi\ThesportsdbApi\Interface\RegionLogoPathResolverInterface;

class OddsApiRegionMapper implements OddsApiRegionMapperInterface
{
    private array $regionKeywords = [
        'USA' => ['usa', 'mls', 'us', 'nfl', 'nba', 'american', 'mlb', 'pga', 'nhl', 'lacrosse', 'usa'],
        'Australia' => ['australia', 'a-league', 'a league', 'aussie'],
        'South Korea' => ['south korea', 'k league', 'k-league', 'kbo'],
        'Japan' => ['japan', 'j league', 'j-league', 'npb'],
        'Europe' => ['europe', 'european', 'uefa', 'euroleague', 'golf_the_open_championship_winner'],
        'World' => ['world', 'fifa', 'world cup', 'international', 'boxing', 'ufc', 'mma'],
        'India' => ['india', 'indian', 'ipl', 'pro kabaddi'],
        'Pakistan' => ['pakistan', 'psl', 'pakistani'],
        'Finland' => ['finland', 'finnish'],
        'Sweden' => ['sweden', 'swedish'],
        'Argentina' => ['argentina'],
        'Austria' => ['austria', 'austrian'],
        'Belgium' => ['belgium', 'belgian'],
        'Brazil' => ['brazil', 'brasileirao'],
        'Chile' => ['chile', 'chilean'],
        'China' => ['china', 'chinese'],
        'South America' => ['CONMEBOL', 'south american'],
        'Denmark' => ['denmark', 'danish'],
        'England' => ['england', 'english', 'efl', 'fa cup'],
        'France' => ['france', 'french'],
        'Germany' => ['germany', 'german'],
        'Greece' => ['greece', 'greek'],
        'Italy' => ['italy', 'italian'],
        'Spain' => ['spain', 'spanish'],
        'Ireland' => ['ireland', 'irish'],
        'Netherlands' => ['netherlands', 'dutch'],
        'Norway' => ['norway', 'norwegian'],
        'Poland' => ['poland', 'polish'],
        'Portugal' => ['portugal', 'portuguese'],
        'Scotland' => ['scotland', 'scottish'],
        'Turkey' => ['turkey', 'turkish'],

    ];
    public function __construct(
        private readonly RegionLogoPathResolverInterface $regionLogoPathResolver,
    ) {
    }
    public function mapRegionToLeague(League $league, array $sportsData): void
    {


    }

    public function mapRegionsToSport(Sport $sport, array $sportsData): void
    {
        foreach ($sportsData as $datum) {
            if (($datum['group'] ?? '') !== $sport->getName()) {
                continue;
            }

            $regionName = $this->resolveRegionName($datum);

            if ($regionName === 'Default Region') {
                continue;
            }

            $region = new Region();
            $region->setName($regionName);
            $region->setLogoPath($this->regionLogoPathResolver->resolve($regionName));
            $sport->addRegion($region);
        }
    }
    private function resolveRegionName(array $sportsData): string
    {
        $text = $sportsData['key'] . ' ' . $sportsData['title'] . ' ' . $sportsData['group'] . ' ' . $sportsData['description'];

        foreach ($this->regionKeywords as $regionName => $keywords) {
            foreach ($keywords as $keyword) {
                // Use \b (boundary) to ensure we match whole words only
                if (preg_match('/\b' . preg_quote(strtolower($keyword), '/') . '\b/', strtolower($text))) {
                    return $regionName;
                }
            }
        }
        return 'Default Region';
    }

}
