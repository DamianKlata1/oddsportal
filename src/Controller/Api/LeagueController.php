<?php

namespace App\Controller\Api;

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

    #[Route('/leagues', name: 'api_get_leagues', methods: ['GET'])]
    public function getLeagues(): JsonResponse
    {
        $leagues = $this->leagueRepository->findAll();
        $response = $this->json($leagues, Response::HTTP_OK);

        $response->setPublic();
        $response->setSharedMaxAge(3600);
        $response->setMaxAge(3600);
        $response->setEtag(md5($response->getContent()));

        if ($response->isNotModified($this->requestStack->getCurrentRequest())) {
            return $response;
        }
        return $response;
    }

}
