<?php

namespace App\DTO\Event;

use App\DTO\Pagination\PaginationResponseDTO;

class EventListResponseDTO
{
    public function __construct(
        /** @var EventDTO[] */
        private readonly array $events,
        private readonly PaginationResponseDTO $pagination
    ) {
    }


    public function getEvents(): array
    {
        return $this->events;
    }

    public function getPagination(): PaginationResponseDTO
    {
        return $this->pagination;
    }
}
