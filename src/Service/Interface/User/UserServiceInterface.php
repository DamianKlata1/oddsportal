<?php

namespace App\Service\Interface\User;

use App\DTO\User\NewUserDTO;
use App\DTO\User\UserEditDTO;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserServiceInterface
{
    public function editUser(User $user, UserEditDTO $userEditDTO): UserInterface;
    public function registerUser(NewUserDTO $userDTO): UserInterface;

}