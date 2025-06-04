<?php

namespace App\Service\Interface\Event;

use App\DTO\Event\EventFiltersDTO;
use App\DTO\Pagination\PaginationDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\DTO\Event\EventListResponseDTO;
use App\Entity\Interface\EventInterface;
use App\Service\Interface\Helper\DeleteResultInterface;
use App\Service\Interface\Entity\EntityServiceInterface;
/**
 * 
 * @extends EntityServiceInterface<EventInterface>
 */
interface EventServiceInterface extends EntityServiceInterface
{
    public function getEvents(EventFiltersDTO $eventFiltersDTO, OutcomeFiltersDTO $outcomeFiltersDTO, PaginationDTO $paginationDTO): EventListResponseDTO;
    public function deletePastEvents(): DeleteResultInterface;

}
