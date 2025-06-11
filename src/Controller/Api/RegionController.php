<?php

namespace App\Controller\Api;

use App\Attribute\HttpCache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegionController extends AbstractController
{
    public function __construct(
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly RequestStack     $requestStack
    )
    {
    }
    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/regions', name: 'api_get_regions', methods: ['GET'])]
    public function getRegions(): Response
    {
        $regions = $this->regionRepository->findAll();

        return $this->json($regions);
    }
}
