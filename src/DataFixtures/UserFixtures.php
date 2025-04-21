<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setEmail('admin@wp.pl');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'password123'));
        $adminUser->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        $user = new User();
        $user->setEmail('user@wp.pl');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();
    }
}
