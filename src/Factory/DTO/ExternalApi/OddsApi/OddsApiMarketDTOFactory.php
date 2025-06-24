<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiMarketDTO;
use App\Factory\Interface\DTO\OddsApiMarketDTOFactoryInterface;
use App\Factory\Interface\DTO\OddsApiOutcomeDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;

class OddsApiMarketDTOFactory implements OddsApiMarketDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
        private readonly OddsApiOutcomeDTOFactoryInterface $oddsApiOutcomeDTOFactory
    ) {
    }
    public function create(
        string $key,
        string $lastUpdate,
        array $outcomes
    ): OddsApiMarketDTO {
        $dto = new OddsApiMarketDTO(
            $key,
            $lastUpdate,
            $outcomes
        );
        $this->validationService->validate($dto);
        return $dto;
    }
    public function createFromArray(array $data): OddsApiMarketDTO
    {
        $dto = $this->create(
            $data['key'],
            $data['last_update'],
            $this->oddsApiOutcomeDTOFactory->createFromArrayList($data['outcomes'])
        );
        return $dto;
    }


    public function createFromArrayList(array $dataList): array
    {
        $dtos = [];
        foreach ($dataList as $data) {
            $dtos[] = $this->createFromArray($data);
        }
        return $dtos;
    }

}
