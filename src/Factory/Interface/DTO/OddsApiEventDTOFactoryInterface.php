<?php

namespace App\Factory\Interface\DTO;

use App\DTO\ExternalApi\OddsApi\OddsApiBookmakerDTO;
use App\DTO\ExternalApi\OddsApi\OddsApiEventDTO;

interface OddsApiEventDTOFactoryInterface
{
    /**
     * @param OddsApiBookmakerDTO[] $bookmakers
     */
    public function create(
        string $id,
        string $commenceTime,
        ?string $homeTeam,
        ?string $awayTeam,
        array $bookmakers,
    ): OddsApiEventDTO;

    public function createFromArray(array $data): OddsApiEventDTO;

    /**
     * @return OddsApiEventDTO[]
     */
    public function createFromArrayList(array $dataList): array;
}
