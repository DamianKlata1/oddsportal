<?php

namespace App\Factory\Interface\DTO;

use App\DTO\ExternalApi\OddsApi\OddsApiOutcomeDTO;

interface OddsApiOutcomeDTOFactoryInterface
{
    public function create(
        string $name,
        string $price
    ): OddsApiOutcomeDTO;
    
    public function createFromArray(array $data): OddsApiOutcomeDTO;

    /**
     * @return OddsApiOutcomeDTO[]
     */
    public function createFromArrayList(array $dataList): array;
}
