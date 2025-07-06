<?php

namespace App\Enum;

use App\Enum\Trait\Enumerable;

enum MarketType: string
{
    use Enumerable;
    case H2H = 'h2h';
    case SPREADS = 'spreads';
    case TOTALS = 'totals';
    case OUTRIGHTS = 'outrights';
    case H2h_LAY = 'h2h_lay';
    case OUTRIGHTS_LAY = 'outrights_lay';
}
