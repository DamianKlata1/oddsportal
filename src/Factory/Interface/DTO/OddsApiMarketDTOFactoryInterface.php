<?php

namespace App\Factory\Interface\DTO;

use App\DTO\ExternalApi\OddsApi\OddsApiMarketDTO;
use App\DTO\ExternalApi\OddsApi\OddsApiOutcomeDTO;

interface OddsApiMarketDTOFactoryInterface
{
    /**
     * @param OddsApiOutcomeDTO[] $outcomes
     */
    public function create(
        string $key,
        string $lastUpdate,
        array $outcomes
    ): OddsApiMarketDTO;

    public function createFromArray(array $data): OddsApiMarketDTO;

    /**
     * @return OddsApiMarketDTO[]
     */
    public function createFromArrayList(array $dataList): array;
}
