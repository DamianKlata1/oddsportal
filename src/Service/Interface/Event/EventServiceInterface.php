<?php

namespace App\Service\Interface\Event;

use App\DTO\Outcome\OutcomeFiltersDTO;

interface EventServiceInterface
{
    public function getEventsForLeague(int $leagueId, OutcomeFiltersDTO $outcomeFiltersDTO): array;

}
