<?php

namespace App\Enum;

use App\Enum\Trait\Enumerable;

enum PriceFormat: string
{
    use Enumerable;
    case DECIMAL = "decimal";
    case AMERICAN = "american";
}
