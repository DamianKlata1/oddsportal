<?php

namespace App\Enum;

enum MarketType: string
{
    case H2H = 'h2h';
    case SPREADS = 'spreads';
    case TOTALS = 'totals';
    case OUTRIGHTS = 'outrights';
    case H2h_LAY = 'h2h_lay';
    case OUTRIGHTS_LAY = 'outrights_lay';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
