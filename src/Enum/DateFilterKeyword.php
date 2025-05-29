<?php

namespace App\Enum;

enum DateFilterKeyword: string
{
    case TODAY = 'today';
    case TOMORROW = 'tomorrow';
    case THIS_WEEK = 'this_week';
    case NEXT_7_DAYS = 'next_7_days';

    public static function fromString(string $value): DateFilterKeyword
    {
        return match ($value) {
            'today' => self::TODAY,
            'tomorrow' => self::TOMORROW,
            'this_week' => self::THIS_WEEK,
            'next_7_days' => self::NEXT_7_DAYS,
            default => throw new \InvalidArgumentException('Invalid  type'),
        };
    }
    public function toString(): string
    {
        return match ($this) {
            self::TODAY => 'today',
            self::TOMORROW => 'tomorrow',
            self::THIS_WEEK => 'this_week',
            self::NEXT_7_DAYS => 'next_7_days',
        };
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
