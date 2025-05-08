<?php

namespace App\Controller\Api;

use App\Repository\Interface\RegionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegionController extends AbstractController
{
    public function __construct(
        private RegionRepositoryInterface $regionRepository,
        private SerializerInterface       $serializer
    )
    {
    }

    #[Route('/regions', name: 'api_get_regions')]
    public function getRegions(): Response
    {
        $regions = $this->regionRepository->findAll();

        return new JsonResponse(
            $this->serializer->serialize($regions, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
