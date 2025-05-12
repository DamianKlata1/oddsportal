<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Interface\Event\EventServiceInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeagueController extends AbstractController
{
    public function __construct(
        private LeagueRepositoryInterface $leagueRepository,
        private EventServiceInterface $eventService,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/leagues', name: 'api_get_leagues', methods: ['GET'])]
    public function getLeagues(): JsonResponse
    {
        $leagues = $this->leagueRepository->findAll();

        return new JsonResponse(
            $this->serializer->serialize($leagues, 'json'),
            Response::HTTP_OK,
            [],
            true
        );

    }

}
