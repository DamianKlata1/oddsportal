<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\Interface\BetRegionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BetRegionController extends AbstractController
{
    public function __construct(
        private BetRegionRepositoryInterface $betRegionRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/bet-regions', name: 'api_bet_regions', methods: ['GET'])]
     public function getBetRegions(): JsonResponse
    {
        $betRegions = $this->betRegionRepository->findAll();
        return new JsonResponse(
            $this->serializer->serialize($betRegions, 'json', ['groups' => 'bet_region_list']),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
