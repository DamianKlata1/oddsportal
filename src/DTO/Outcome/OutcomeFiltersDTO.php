<?php

namespace App\DTO\Outcome;
use Symfony\Component\Validator\Constraints as Assert;

class OutcomeFiltersDTO
{
    public function __construct(
        #[Assert\Type('string')]
        private readonly ?string $betRegion = 'eu',
        #[Assert\Type('string')]
        private readonly ?string $priceFormat = 'decimal',

    ) {
    }
    public function getBetRegion(): ?string
    {
        return $this->betRegion;
    }

    public function getPriceFormat(): ?string
    {
        return $this->priceFormat;
    }
}
