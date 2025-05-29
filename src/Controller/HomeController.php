<?php

namespace App\Controller;

//use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        //        private readonly OddsApiClientInterface $client
    ) {
    }

    #[Route('/{path<.*>}', name: 'app_home', priority: -20)]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
