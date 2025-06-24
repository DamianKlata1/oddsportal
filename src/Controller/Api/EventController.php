<?php

namespace App\Controller\Api;

use App\DTO\Event\EventFiltersDTO;
use App\DTO\Outcome\OutcomeFiltersDTO;
use App\DTO\Pagination\PaginationDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class EventController extends AbstractController
{
    public function __construct(
        private readonly EventServiceInterface $eventService,
    ) {
    }
    #[Route('events', name: 'api_get_events', methods: ['GET'])]
    public function getEvents(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] EventFiltersDTO $eventFiltersDTO,
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] OutcomeFiltersDTO $outcomeFiltersDTO,
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] PaginationDTO $paginationDTO
    ): JsonResponse {
        $events = $this->eventService->getEvents($eventFiltersDTO, $outcomeFiltersDTO, $paginationDTO);
        return $this->json($events, Response::HTTP_OK);
    }
    #[Route('events/{eventId}/best-outcomes', name: 'api_get_event_best_outcomes', methods: ['GET'])]
    public function getEventBestOutcomes(
        int $eventId,
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] OutcomeFiltersDTO $outcomeFiltersDTO
    ): JsonResponse {
        $outcomes = $this->eventService->getEventBestOutcomes($eventId, $outcomeFiltersDTO);
        return $this->json($outcomes, Response::HTTP_OK);
    }
}
