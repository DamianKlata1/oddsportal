<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Interface\User\UserServiceInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class FavoriteLeagueController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }
    #[Route('/me/favorite-leagues', name: 'app_get_favorite_leagues', methods: ['GET'])]
    public function getFavoriteLeagues(): JsonResponse
    {
        $leagues = $this->userService->getFavoriteLeagues($this->getUser());
        return $this->json($leagues);
    }
    #[Route('/me/favorite-leagues', name: 'app_add_favorite_league', methods: ['POST'])]
    public function addFavoriteLeague(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] int $leagueId
    ): JsonResponse {
        $this->userService->addFavoriteLeague($this->getUser(), $leagueId);

        return $this->json(['message' => 'League added to favorites successfully.'], Response::HTTP_CREATED);
    }

    #[Route('/me/favorite-leagues/{id}', name: 'app_delete_favorite_league', methods: ['DELETE'])]
    public function deleteFavoriteLeague(
        int $leagueId
    ): JsonResponse {
        $this->userService->removeFavoriteLeague($this->getUser(), $leagueId);

        return $this->json(['message' => 'League removed from favorites successfully.']);
    }
}
