<?php

namespace App\Repository\Interface;

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
/**
 * @extends RepositoryInterface<User>
 */
interface UserRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
    public function isEmailExists(string $email): bool;
    public function countRegisteredInLast7Days(): int;

}