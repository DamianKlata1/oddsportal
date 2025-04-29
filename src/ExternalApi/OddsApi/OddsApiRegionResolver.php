<?php

namespace App\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\ExternalApi\Interface\OddsApi\OddsApiRegionResolverInterface;

class OddsApiRegionResolver implements OddsApiRegionResolverInterface
{
    private const REGION_KEYWORDS = [
        'USA' => ['usa', 'mls', 'us', 'nfl', 'nba', 'american', 'mlb', 'pga', 'nhl', 'lacrosse'],
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

    public function resolveRegionName(OddsApiSportsDataDTO $sportsDataDto): string
    {
        if ($sportsDataDto->hasOutrights()) {
            return 'Outrights';
        }
        $text = implode(' ', [
            $sportsDataDto->getKey(),
            $sportsDataDto->getTitle(),
            $sportsDataDto->getGroup(),
            $sportsDataDto->getDescription()
        ]);

        return $this->matchRegion($text) ?? 'Default';
    }
    private function matchRegion(string $text): ?string
    {
        foreach (self::REGION_KEYWORDS as $regionName => $keywords) {
            foreach ($keywords as $keyword) {
                if ($this->containsKeyword($text, $keyword)) {
                    return $regionName;
                }
            }
        }
        return null;
    }
    private function containsKeyword(string $text, string $keyword): bool
    {
        return preg_match(
            '/\b' . preg_quote(
                strtolower($keyword),
                '/'
            ) . '\b/',
            strtolower($text)
        ) === 1;
    }

}
