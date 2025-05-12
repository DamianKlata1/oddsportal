<?php

namespace App\Enum;

enum MarketType: string
{
    case H2H = 'h2h';
    case SPREADS = 'spreads';
    case TOTALS = 'totals';
    case OUTRIGHTS = 'outrights';

    public static function fromString(string $value): MarketType
    {
        return match ($value) {
            'h2h' => self::H2H,
            'spreads' => self::SPREADS,
            'totals' => self::TOTALS,
            'outrights' => self::OUTRIGHTS,
            default => throw new \InvalidArgumentException('Invalid market type'),
        };
    }
    public function toString(): string
    {
        return match ($this) {
            self::H2H => 'h2h',
            self::SPREADS => 'spreads',
            self::TOTALS => 'totals',
            self::OUTRIGHTS => 'outrights',
        };
    }
}
