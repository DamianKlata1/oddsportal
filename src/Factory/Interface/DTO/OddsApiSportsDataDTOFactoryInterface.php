<?php

namespace App\Factory\Interface\DTO;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;



interface OddsApiSportsDataDTOFactoryInterface
{
    public function create(
        string $key,
        string $group,
        string $title,
        string $description,
        bool $active,
        bool $hasOutrights
    ): OddsApiSportsDataDTO;

    public function createFromArray(array $data): OddsApiSportsDataDTO;
    
    /**
     * @return OddsApiSportsDataDTO[]
     */
    public function createFromArrayList(array $dataList): array;
}
