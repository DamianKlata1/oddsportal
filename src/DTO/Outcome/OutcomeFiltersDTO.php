<?php

namespace App\DTO\Outcome;
use Symfony\Component\Validator\Constraints as Assert;

class OutcomeFiltersDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $betRegion,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $priceFormat,

    ) {
    }
    public function getBetRegion(): string
    {
        return $this->betRegion;
    }

    public function getPriceFormat(): string
    {
        return $this->priceFormat;
    }
}
