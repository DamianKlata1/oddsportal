<?php

namespace App\Repository\Interface;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface
{
    public function save(UserInterface $user): UserInterface;
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
    public function isEmailExists(string $email): bool;

}