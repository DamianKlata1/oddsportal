<?php

namespace App\DTO\ExternalApi\OddsApi;
use Symfony\Component\Validator\Constraints as Assert;


class OddsApiOutcomeDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $name,
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $price
    ) {
    }
    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
