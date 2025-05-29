<?php

namespace App\Service\Interface\Event;

use App\DTO\Event\EventFiltersDTO;
use App\DTO\Pagination\PaginationDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\DTO\Event\EventListResponseDTO;
use App\Service\Interface\Helper\DeleteResultInterface;

interface EventServiceInterface
{
    public function getEvents(EventFiltersDTO $eventFiltersDTO, OutcomeFiltersDTO $outcomeFiltersDTO, PaginationDTO $paginationDTO): EventListResponseDTO;
    public function deletePastEvents(): DeleteResultInterface;

}
