<?php

namespace App\Repository\Interface;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface extends ObjectRepository
{
    public function save(UserInterface $user): UserInterface;
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
    public function isEmailExists(string $email): bool;

}