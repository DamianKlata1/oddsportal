<?php

namespace App\ExternalApi\ThesportsdbApi;

use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;

class ThesportsdbApiRegionLogoPathResolver implements RegionLogoPathResolverInterface
{
    public function __construct(
        private readonly string $thesportsdbFlagsUrl,
    )
    {
    }

    public function resolve(string $name): string
    {
        $nameNormalized = $this->normalizeName($name);
        $url = "{$this->thesportsdbFlagsUrl}/{$nameNormalized}.png";
        return $url;
    }

    private function normalizeName(string $name): string
    {
        $eachWordUppercasedName = ucwords($name);
        $nameWithDashes = str_replace(' ', '-', $eachWordUppercasedName);
        return strtolower($nameWithDashes);
    }
}