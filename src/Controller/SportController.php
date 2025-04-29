<?php

namespace App\Controller;

use App\Repository\Interface\RegionRepositoryInterface;
use App\Repository\Interface\SportRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SportController extends AbstractController
{
    public function __construct(
        private SportRepositoryInterface  $sportRepository,
        private RegionRepositoryInterface $regionRepository,
        private SerializerInterface       $serializer
    )
    {
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
        $regions = $this->regionRepository->findBy(['sport' => $sportId]);
        return new JsonResponse(
            $this->serializer->serialize($regions, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
