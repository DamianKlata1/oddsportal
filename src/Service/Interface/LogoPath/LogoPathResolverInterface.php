<?php

namespace App\Service\Interface\LogoPath;

interface LogoPathResolverInterface
{
    public function resolve(string $name): string;

}