<?php

namespace App\Controller\Api;

use App\Attribute\HttpCache;
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
    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/bet-regions', name: 'api_bet_regions', methods: ['GET'])]
    public function getBetRegions(): JsonResponse
    {
        $betRegions = $this->betRegionRepository->findAll();

        return $this->json($betRegions, Response::HTTP_OK, [], ['groups' => 'bet_region_list']);
    }
}
