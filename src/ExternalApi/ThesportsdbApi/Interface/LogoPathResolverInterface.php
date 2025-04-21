<?php

namespace App\ExternalApi\ThesportsdbApi\Interface;

interface LogoPathResolverInterface
{
    public function resolve(string $name): string;

}