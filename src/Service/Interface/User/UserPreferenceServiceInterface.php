<?php

namespace App\Service\Interface\User;

use App\Entity\User;


interface UserPreferenceServiceInterface
{
    public function getFavoriteLeagues(User $user): array;
    public function addFavoriteLeague(User $user, int $leagueId): bool;
    public function removeFavoriteLeague(User $user, int $leagueId): void;
}
