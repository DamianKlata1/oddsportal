<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\DTO\ExternalApi\OddsApi\OddsApiEventDTO;
use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\Factory\Interface\DTO\OddsApiEventDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;
use App\Factory\Interface\DTO\OddsApiBookmakerDTOFactoryInterface;

class OddsApiEventDTOFactory implements OddsApiEventDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
        private readonly OddsApiBookmakerDTOFactoryInterface $oddsApiBookmakerDTOFactory
    ) {
    }
    public function create(
        string $id,
        string $commenceTime,
        ?string $homeTeam,
        ?string $awayTeam,
        array $bookmakers,
    ): OddsApiEventDTO {
        $dto = new OddsApiEventDTO(
            $id,
            $commenceTime,
            $homeTeam,
            $awayTeam,
            $bookmakers,
        );
        $this->validationService->validate($dto);
        return $dto;
    }
    public function createFromArray(array $data): OddsApiEventDTO
    {
        $dto = $this->create(
            $data['id'],
            $data['commence_time'],
            $data['home_team'],
            $data['away_team'],
            $this->oddsApiBookmakerDTOFactory->createFromArrayList($data['bookmakers'])
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
