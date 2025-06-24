<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiBookmakerDTO;
use App\Factory\Interface\DTO\OddsApiMarketDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;
use App\Factory\Interface\DTO\OddsApiBookmakerDTOFactoryInterface;

class OddsApiBookmakerDTOFactory implements OddsApiBookmakerDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
        private readonly OddsApiMarketDTOFactoryInterface $oddsApiMarketDTOFactory
    ) {
    }
    public function create(
        string $title,
        array $markets
    ): OddsApiBookmakerDTO {
        $dto = new OddsApiBookmakerDTO(
            $title,
            $markets,
        );
        $this->validationService->validate($dto);
        return $dto;
    }
    public function createFromArray(array $data): OddsApiBookmakerDTO
    {
        $dto = new OddsApiBookmakerDTO(
            $data['title'],
            $this->oddsApiMarketDTOFactory->createFromArrayList($data['markets'])
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
