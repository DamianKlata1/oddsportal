<?php

namespace App\Enum\Trait;

trait Enumerable
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
