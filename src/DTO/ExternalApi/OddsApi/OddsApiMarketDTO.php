<?php

namespace App\DTO\ExternalApi\OddsApi;
use Symfony\Component\Validator\Constraints as Assert;

class OddsApiMarketDTO
{
    public function __construct(
        
        private readonly string $key,
        private readonly string $lastUpdate,
        /**
         * @var OddsApiOutcomeDTO[]
         */
        private readonly array $outcomes,
    ) {
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**

     * @return  OddsApiOutcomeDTO[]
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }
}
