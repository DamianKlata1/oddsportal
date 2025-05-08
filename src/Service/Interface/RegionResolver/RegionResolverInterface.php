<?php

namespace App\Service\Interface\RegionResolver;

interface RegionResolverInterface
{
    public function resolve(string $text): string;
}
