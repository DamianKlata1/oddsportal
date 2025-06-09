<?php

namespace App\Controller\Api;

use App\Enum\PriceFormat;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PriceFormatController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }
    #[Route('/price-formats', name: 'api_get_price_format', methods: ['GET'])]
    public function getPriceFormats(): JsonResponse
    {
        $response = $this->json(PriceFormat::cases());

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
