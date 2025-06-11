<?php

namespace App\Controller\Api;

use App\Attribute\HttpCache;
use App\Enum\DateFilterKeyword;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DateFilterKeywordController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }
    #[HttpCache(maxage: 3600, smaxage: 3600, public: true)]
    #[Route('/date-filter-keywords', name: 'api_get_date_filter_keywords', methods: ['GET'])]
    public function getDateFilterKeywords(): JsonResponse
    {
        return $this->json(DateFilterKeyword::cases());;
    }
}
