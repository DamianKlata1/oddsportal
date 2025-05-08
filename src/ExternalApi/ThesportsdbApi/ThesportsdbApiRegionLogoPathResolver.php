<?php

namespace App\ExternalApi\ThesportsdbApi;

use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;

class ThesportsdbApiRegionLogoPathResolver implements RegionLogoPathResolverInterface
{
    public function __construct(
        private readonly string $thesportsdbFlagsUrl,
        private readonly string $worldLogoUrl,
    ) {
    }
    public function resolve(string $name): string
    {
        $normalized = $this->normalizeName($name);
        $url = sprintf(
            '%s/%s.png',
            rtrim(
                $this->thesportsdbFlagsUrl,
                '/'
            ),
            $normalized
        );

        return $this->urlExists($url) ? $url : $this->worldLogoUrl;
    }

    private function normalizeName(string $name): string
    {
        // Normalize to 'country-name' format expected by the API
        $withDashes = str_replace(' ', '-', $name); // → "United-States"
        return strtolower($withDashes); // → "united-states"
    }
    private function urlExists(string $url): bool
    {
        $headers = @get_headers($url, true); // Suppress warning if URL is invalid
        return is_array($headers) && str_starts_with($headers[0], 'HTTP/1.1 200');
    }
}