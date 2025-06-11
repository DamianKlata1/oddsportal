<?php

namespace App\Controller\Api;

use App\Attribute\HttpCache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\Region\RegionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SportController extends AbstractController
{
    public function __construct(
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionServiceInterface $regionService,
        private readonly RequestStack $requestStack
    ) {
    }

    #[Route('/sports', name: 'api_get_sports', methods: ['GET'])]
    public function getSports(): JsonResponse
    {
        $sports = $this->sportRepository->findAll();
        $response = $this->json($sports, Response::HTTP_OK, [], ['groups' => 'sport_list']);

        $response->setPublic();
        $response->setSharedMaxAge(3600);
        $response->setMaxAge(3600);
        $response->setEtag(md5($response->getContent()));

        if ($response->isNotModified($this->requestStack->getCurrentRequest())) {
            return $response;
        }
        return $response;
    }

    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/sports/{sportId}/regions', name: 'api_get_regions_for_sport', methods: ['GET'])]
    public function getRegionsForSport(int $sportId): JsonResponse
    {
        $regionDtoList = $this->regionService->getRegionsWithActiveLeagues($sportId);
        return $this->json($regionDtoList, Response::HTTP_OK, []);;
    }
}
