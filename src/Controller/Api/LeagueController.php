<?php

namespace App\Controller\Api;

use App\Attribute\HttpCache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Interface\LeagueRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeagueController extends AbstractController
{
    public function __construct(
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly RequestStack $requestStack
    ) {
    }
    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/leagues', name: 'api_get_leagues', methods: ['GET'])]
    public function getLeagues(): JsonResponse
    {
        $leagues = $this->leagueRepository->findAll();

        return $this->json($leagues, Response::HTTP_OK);
    }

}
