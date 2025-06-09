<?php

namespace App\Controller\Api;

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
    #[Route('/date-filter-keywords', name: 'api_get_date_filter_keywords', methods: ['GET'])]
    public function getDateFilterKeywords(): JsonResponse
    {
        $response = $this->json(DateFilterKeyword::cases());

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
