<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\Exception\InvalidSportsDataException;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Factory\Interface\DTO\OddsApiSportsDataDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;

class OddsApiSportsDataDTOFactory implements OddsApiSportsDataDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
    )
    {
    }
    public function create(
        string $key,
        string $group,
        string $title,
        string $description,
        bool $active,
        bool $hasOutrights
    ): OddsApiSportsDataDTO 
    {
        $dto = new OddsApiSportsDataDTO(
            $key,
            $group,
            $title,
            $description,
            $active,
            $hasOutrights
        );
        $this->validationService->validate($dto);
        return $dto;
    }
    public function createFromArray(array $data): OddsApiSportsDataDTO
    {
        $dto = $this->create(
            $data['key'],
            $data['group'],
            $data['title'],
            $data['description'],
            (bool) $data['active'],
            (bool) $data['has_outrights']
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