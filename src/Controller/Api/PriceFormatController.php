<?php

namespace App\Controller\Api;

use App\Enum\PriceFormat;
use App\Attribute\HttpCache;
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
    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/price-formats', name: 'api_get_price_format', methods: ['GET'])]
    public function getPriceFormats(): JsonResponse
    {

        return $this->json(PriceFormat::cases());
    }
}
