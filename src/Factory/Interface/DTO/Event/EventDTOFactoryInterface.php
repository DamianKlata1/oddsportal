<?php

namespace App\Factory\Interface\DTO\Event;

use App\DTO\Event\EventDTO;
use App\DTO\Outcome\OutcomeDTO;

interface EventDTOFactoryInterface
{
    public function create(
        int $id,
        \DateTimeImmutable $commenceTime,
        string $homeTeam,
        string $awayTeam,
        /** 
         * @var OutcomeDTO[]
         */
        array $bestOdds,
    ): EventDTO;
}
