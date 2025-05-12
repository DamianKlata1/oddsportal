<?php

namespace App\Service\Interface\Outcome;

use App\Enum\PriceFormat;

interface PriceFormatConverterInterface
{
    public function apply(string $price, PriceFormat $format): string;

}
