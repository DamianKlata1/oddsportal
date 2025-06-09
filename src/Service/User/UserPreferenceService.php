<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\League;
use App\DTO\League\LeagueDTO;
use App\Exception\ValidationException;
use App\Factory\Interface\LeagueDTOFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Interface\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\Interface\League\LeagueServiceInterface;
use App\Service\Interface\User\UserPreferenceServiceInterface;

class UserPreferenceService implements UserPreferenceServiceInterface
{
    public function __construct(
        private readonly LeagueServiceInterface $leagueService,
        private readonly LeagueDTOFactoryInterface $leagueDTOFactoryInterface,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }
    /**
     * 
     * @param User $user
     * @return LeagueDTO[]
     */
    public function getFavoriteLeagues(User $user): array
    {
        return array_map(
            fn(League $league) => $this->leagueDTOFactoryInterface->createFromEntity($league),
            $user->getFavoriteLeagues()
                ->filter(fn(League $l) => $l->isActive())
                ->toArray()
        );
    }
    /**
     * @throws ValidationException
     */
    public function addFavoriteLeague(User $user, int $leagueId): bool
    {
        /** @var League */
        $league = $this->leagueService->findOrFail($leagueId);

        if ($user->getFavoriteLeagues()->contains($league)) {
            return false; 
        }

        $user->addFavoriteLeague($league);
        $this->userRepository->save($user, flush: true);
        return true; 
    }
    /**
     * @throws ValidationException
     */
    public function removeFavoriteLeague(User $user, int $leagueId): void
    {
        /** @var League */
        $league = $this->leagueService->findOrFail($leagueId);
        if (!$user->getFavoriteLeagues()->contains($league)) {
            throw new ValidationException(sprintf(
                "League %s is not in user %s's favorite leagues",
                $league->getName(),
                $user->getEmail()
            ), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->removeFavoriteLeague($league);
        $this->userRepository->save($user, flush: true);
    }
}
