<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\Region\RegionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SportController extends AbstractController
{
    public function __construct(
        private SportRepositoryInterface $sportRepository,
        private RegionServiceInterface $regionService,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/sports', name: 'api_get_sports', methods: ['GET'])]
    public function getSports(): JsonResponse
    {
        $sports = $this->sportRepository->findAll();
        return new JsonResponse(
            $this->serializer->serialize($sports, 'json', ['groups' => ['sport_list']]),
            Response::HTTP_OK,
            [],
            true
        );
    }


    #[Route('/sports/{sportId}/regions', name: 'api_get_regions_for_sport', methods: ['GET'])]
    public function getRegionsForSport(int $sportId): JsonResponse
    {
        $regionDtoList = $this->regionService->getRegionsWithActiveLeagues($sportId);

        return $this->json($regionDtoList);
    }
}
