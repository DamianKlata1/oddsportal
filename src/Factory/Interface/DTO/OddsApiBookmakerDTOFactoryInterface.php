<?php

namespace App\Factory\Interface\DTO;

use App\DTO\ExternalApi\OddsApi\OddsApiBookmakerDTO;
use App\DTO\ExternalApi\OddsApi\OddsApiMarketDTO;

interface OddsApiBookmakerDTOFactoryInterface
{
    /**
     * @param OddsApiMarketDTO[] $markets
     */
    public function create(
        string $title,
        array $markets
    ): OddsApiBookmakerDTO;

    public function createFromArray(array $data): OddsApiBookmakerDTO;

    /**
     * @return OddsApiBookmakerDTO[]
     */
    public function createFromArrayList(array $dataList): array;

}
