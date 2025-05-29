<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\PriceFormat;

class PriceFormatController extends AbstractController
{
    #[Route('/price-formats', name: 'api_get_price_format', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json(
             PriceFormat::cases()
        );
    }
}
