<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\DateFilterKeyword;

class DateFilterKeywordController extends AbstractController
{
    #[Route('/date-filter-keywords', name: 'api_get_date_filter_keywords', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json(
            DateFilterKeyword::cases()
        );
    }
}
