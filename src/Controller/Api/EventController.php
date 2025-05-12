<?php

namespace App\Controller\Api;

use App\DTO\Outcome\OutcomeFiltersDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\Interface\EventRepositoryInterface;
use App\Service\Interface\Event\EventServiceInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly EventServiceInterface $eventService,
        private readonly SerializerInterface $serializer
    ) {
    }
    #[Route('/events', name: 'api_events', methods: ['GET'])]
    public function getEvents(): JsonResponse
    {
        $events = $this->eventRepository->findAll();
        return new JsonResponse(
            $this->serializer->serialize($events, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
    #[Route('events/{leagueId}', name: 'api_get_league_events', methods: ['GET'])]
    public function getEventsForLeague(
        int $leagueId,
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] OutcomeFiltersDTO $outcomeFiltersDTO,
    ): JsonResponse 
    {
        $events = $this->eventService->getEventsForLeague($leagueId, $outcomeFiltersDTO);

        return new JsonResponse(
            $this->serializer->serialize($events, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
