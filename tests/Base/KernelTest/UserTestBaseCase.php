<?php

namespace App\Tests\Base\KernelTest;

use App\Repository\UserRepositoryInterface;
use App\Service\UserService;
use App\Tests\Trait\UserTestConstantsTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class UserTestBaseCase extends DatabaseDependantTestCase
{
    use UserTestConstantsTrait;
    protected UserService $userService;
    protected UserRepositoryInterface $userRepository;
    protected UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();

        $this->userService = $container->get(UserService::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

    }

}