<?php

namespace App\Service\Interface\User;

use App\DTO\User\NewUserDTO;
use App\DTO\User\UserEditDTO;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\Interface\Entity\EntityServiceInterface;
/**
 * @extends EntityServiceInterface<UserInterface>
 */
interface UserServiceInterface extends EntityServiceInterface
{
    public function editUser(UserInterface $user, UserEditDTO $userEditDTO): UserInterface;
    public function registerUser(NewUserDTO $userDTO): UserInterface;
    public function getFavoriteLeagues(UserInterface $user): array;
    public function addFavoriteLeague(UserInterface $user, int $leagueId): void;
    public function removeFavoriteLeague(UserInterface $user, int $leagueId): void;


}