<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\Exception\InvalidSportsDataException;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\Service\Interface\ValidationServiceInterface;

class OddsApiSportsDataDTOFactory implements SportsDataDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
    )
    {
    }
    public function createFromArray(array $data): OddsApiSportsDataDTO
    {
        $dto = new OddsApiSportsDataDTO(
            $data['key'],
            $data['group'],
            $data['title'],
            $data['description'],
            (bool) $data['active'],
            (bool) $data['has_outrights']
        );
        $this->validationService->validate($dto);
        return $dto;
    }

    /**
     * @return OddsApiSportsDataDTO[]
     * @throws InvalidSportsDataException
     */
    public function createFromArrayList(array $dataList): array
    {
        $dtos = [];
        foreach ($dataList as $data) {
            $dtos[] = $this->createFromArray($data);
        }
        return $dtos;
    }
}