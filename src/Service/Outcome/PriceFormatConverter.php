<?php

namespace App\Service\Outcome;

use App\Enum\PriceFormat;
use App\Service\Interface\Outcome\PriceFormatConverterInterface;

class PriceFormatConverter implements PriceFormatConverterInterface
{
    public function apply(string $price, PriceFormat $format = PriceFormat::DECIMAL): string
    {
        return match ($format) {
            PriceFormat::DECIMAL => $price,
            PriceFormat::AMERICAN => $this->convertDecimalToAmerican($price),
            default => throw new \InvalidArgumentException('Invalid format'),
        };

    }
    private function convertDecimalToAmerican(string $price): string
    {
        return (float) $price >= 2.0
            ? '+' . number_format(( (float) $price - 1) * 100, 0)
            : number_format(-100 / ( (float) $price - 1), 0);
    }

}
