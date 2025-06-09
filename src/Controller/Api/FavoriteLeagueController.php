<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Service\Interface\User\UserPreferenceServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class FavoriteLeagueController extends AbstractController
{
    public function __construct(
        private readonly UserPreferenceServiceInterface $userPreferenceService,
    ) {
    }
    #[Route('/me/favorite-leagues', name: 'api_get_favorite_leagues', methods: ['GET'])]
    public function getFavoriteLeagues(): JsonResponse
    {
        $leagues = $this->userPreferenceService->getFavoriteLeagues($this->getUser());
        return $this->json($leagues);
    }
    #[Route('/me/favorite-leagues/{leagueId}', name: 'api_add_favorite_league', methods: ['PUT'])]
    public function addFavoriteLeague(int $leagueId): JsonResponse
    {
        $wasAdded = $this->userPreferenceService->addFavoriteLeague($this->getUser(), $leagueId);
        if ($wasAdded) {
            return $this->json(['message' => 'League added to favorites successfully.'], Response::HTTP_CREATED);
        } else {
            return $this->json(['message' => 'League is already a favorite.'], Response::HTTP_OK);
        }
    }
    #[Route('/me/favorite-leagues/{leagueId}', name: 'api_delete_favorite_league', methods: ['DELETE'])]
    public function deleteFavoriteLeague(int $leagueId): JsonResponse
    {
        $this->userPreferenceService->removeFavoriteLeague($this->getUser(), $leagueId);

        return $this->json(['message' => 'League removed from favorites successfully.']);
    }
}
