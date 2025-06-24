<?php

namespace App\Enum;

enum PriceFormat: string
{
    case DECIMAL = "decimal";
    case AMERICAN = "american";
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}
