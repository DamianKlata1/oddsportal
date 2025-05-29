<?php

namespace App\Enum;

enum PriceFormat: string
{
    case DECIMAL = "decimal";
    case AMERICAN = "american";
    public static function fromString(string $value): self
    {
        return match ($value) {
            'decimal' => self::DECIMAL,
            'american' => self::AMERICAN,
            default => throw new \InvalidArgumentException('Invalid price format'),
        };
    }
    public function toString(): string
    {
        return match ($this) {
            self::DECIMAL => 'decimal',
            self::AMERICAN => 'american',
        };
    }
      public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    

}
