<?php

namespace App\Enum;

use App\Enum\Trait\Enumerable;

enum DateFilterKeyword: string
{
    use Enumerable;
    case TODAY = 'today';
    case TOMORROW = 'tomorrow';
    case THIS_WEEK = 'this_week';
    case NEXT_7_DAYS = 'next_7_days';
}
