<?php

namespace App\Controller\Api;

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

    #[Route('/regions', name: 'api_get_regions', methods: ['GET'])]
    public function getRegions(): Response
    {
        $regions = $this->regionRepository->findAll();
        $response = $this->json($regions);
        
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
