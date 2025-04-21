<?php

namespace App\Controller;

use App\Repository\Interface\LeagueRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LeagueController extends AbstractController
{
    public function __construct(
        private LeagueRepositoryInterface $leagueRepository,
        private SerializerInterface       $serializer
    )
    {
    }

    #[Route('/leagues', name: 'api_get_leagues')]
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
