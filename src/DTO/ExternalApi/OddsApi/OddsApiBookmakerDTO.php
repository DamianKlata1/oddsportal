<?php

namespace App\DTO\ExternalApi\OddsApi;
use Symfony\Component\Validator\Constraints as Assert;

class OddsApiBookmakerDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $title,
        /**
         * @var OddsApiMarketDTO[]
         */
        private readonly array $markets
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return OddsApiMarketDTO[]
     */
    public function getMarkets(): array
    {
        return $this->markets;
    }
}
