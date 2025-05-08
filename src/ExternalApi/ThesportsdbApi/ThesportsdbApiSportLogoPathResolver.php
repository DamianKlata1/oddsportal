<?php

namespace App\ExternalApi\ThesportsdbApi;

use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;


class ThesportsdbApiSportLogoPathResolver implements SportLogoPathResolverInterface
{
    private const CATEGORY_MAP = [
        'boxing' => 'fighting',
        'wrestling' => 'fighting',
        'mixed martial arts' => 'fighting',
        'aussie rules' => 'australian football',
        'rugby league' => 'rugby',
        'rugby union' => 'rugby',
        'politics' => 'gambling',
    ];

    public function __construct(
        private readonly string $thesportsdbIconsUrl,
    ) {}

    public function resolve(string $name): string
    {
        $normalized = $this->normalizeName($name);
        return sprintf('%s/sports/%s.png', $this->thesportsdbIconsUrl, $normalized);
    }

    private function normalizeName(string $name): string
    {
        $lowerName = strtolower(trim($name));
        $category = self::CATEGORY_MAP[$lowerName] ?? $lowerName;
        return str_replace(' ', '', $category);
    }
}