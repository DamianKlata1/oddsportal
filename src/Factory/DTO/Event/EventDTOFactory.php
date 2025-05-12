<?php

namespace App\Factory\DTO\ExternalApi\OddsApi;

use App\DTO\Event\EventDTO;
use App\DTO\Outcome\OutcomeDTO;
use App\Factory\Interface\DTO\Event\EventDTOFactoryInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;

class EventDTOFactory implements EventDTOFactoryInterface
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
    ) {
    }
    public function create(
        int $id,
        \DateTimeImmutable $commenceTime,
        string $homeTeam,
        string $awayTeam,
        /** 
         * @var OutcomeDTO[]
         */
        array $bestOdds,
    ): EventDTO {
        $dto = new EventDTO(
            $id,
            $commenceTime,
            $homeTeam,
            $awayTeam,
            $bestOdds,
        );
        $this->validationService->validate($dto);
        return $dto;
    }
}