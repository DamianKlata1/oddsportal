<?php

namespace App\Service;

use App\DTO\NewUserDTO;
use App\DTO\UserEditDTO;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\UserRepositoryInterface;
use App\Service\Interface\UserServiceInterface;
use App\Service\Interface\ValidationServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly ValidationServiceInterface  $validationService,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function editUser(User $user, UserEditDTO $userEditDTO): UserInterface
    {
        $this->validationService->validate($userEditDTO);
        if (!$this->passwordHasher->isPasswordValid($user, $userEditDTO->getCurrentPassword())) {
            throw new ValidationException(
                'Current password is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        if ($userEditDTO->getEmail()) {
            $user->setEmail($userEditDTO->getEmail());
        }
        if ($userEditDTO->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userEditDTO->getPassword()));
        }
        $this->validationService->validate($user);

        return $this->userRepository->save($user);
    }

    /**
     * @throws ValidationException
     */
    public function registerUser(NewUserDTO $userDTO): UserInterface
    {
        $this->validationService->validate($userDTO);
        if ($this->userRepository->isEmailExists($userDTO->getEmail())) {
            throw new ValidationException(
                'There is already an account with this email.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $user = new User();
        $user->setEmail($userDTO->getEmail());
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userDTO->getPassword()
            )
        );
        $user->setRoles(['ROLE_USER']);

        return $this->userRepository->save($user);
    }


}