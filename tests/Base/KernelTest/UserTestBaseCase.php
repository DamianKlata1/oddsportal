<?php

namespace App\Tests\Base\KernelTest;

use App\Repository\Interface\UserRepositoryInterface;
use App\Service\Interface\UserServiceInterface;
use App\Tests\Trait\UserTestConstantsTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class UserTestBaseCase extends DatabaseDependantTestCase
{
    use UserTestConstantsTrait;
    protected UserServiceInterface $userService;
    protected UserRepositoryInterface $userRepository;
    protected UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();

        $this->userService = $container->get(UserServiceInterface::class);
        $this->userRepository = $container->get(UserRepositoryInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

    }

}