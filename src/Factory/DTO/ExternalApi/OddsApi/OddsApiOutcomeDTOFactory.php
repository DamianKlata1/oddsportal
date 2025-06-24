<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiOutcomeDTO;
use App\Factory\Interface\DTO\OddsApiOutcomeDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;

class OddsApiOutcomeDTOFactory implements OddsApiOutcomeDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService
    ) {
    }
    public function create(
        string $name,
        string $price
    ): OddsApiOutcomeDTO {
        $dto = new OddsApiOutcomeDTO(
            $name,
            $price
        );
        $this->validationService->validate($dto);
        return $dto;
    }
    public function createFromArray(array $data): OddsApiOutcomeDTO
    {
        $dto = $this->create(
            $data['name'],
            $data['price'],
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
