<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Interface\BetRegionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BetRegionController extends AbstractController
{
    public function __construct(
        private readonly BetRegionRepositoryInterface $betRegionRepository,
        private readonly RequestStack $requestStack
    ) {
    }

    #[Route('/bet-regions', name: 'api_bet_regions', methods: ['GET'])]
    public function getBetRegions(): JsonResponse
    {
        $betRegions = $this->betRegionRepository->findAll();
        $response = $this->json($betRegions, Response::HTTP_OK, [], ['groups' => 'bet_region_list']);

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
