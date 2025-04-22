<?php

namespace App\ExternalApi\ThesportsdbApi;

use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;


class ThesportsdbApiSportLogoPathResolver implements SportLogoPathResolverInterface
{
    public function __construct(
        private readonly string $thesportsdbIconsUrl,
    )
    {
    }

    public function resolve(string $name): string
    {
        $nameNormalized = $this->normalizeName($name);
        $url = "{$this->thesportsdbIconsUrl}/sports/{$nameNormalized}.png";
        return $url;
    }
    private function normalizeName(string $name): string
    {
        $nameWithoutSpaces = str_replace(' ', '', $name);
        return strtolower($nameWithoutSpaces);
    }
}