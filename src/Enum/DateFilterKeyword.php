<?php

namespace App\Enum;

enum DateFilterKeyword: string
{
    case TODAY = 'today';
    case TOMORROW = 'tomorrow';
    case THIS_WEEK = 'this_week';
    case NEXT_7_DAYS = 'next_7_days';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
