<?php

namespace App\Service\User;

use App\DTO\League\LeagueDTO;
use App\DTO\User\NewUserDTO;
use App\DTO\User\UserEditDTO;
use App\Entity\League;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\Interface\UserRepositoryInterface;
use App\Service\Entity\AbstractEntityService;
use App\Service\Interface\League\LeagueServiceInterface;
use App\Service\Interface\User\UserServiceInterface;
use App\Service\Interface\Validation\ValidationServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService extends AbstractEntityService implements UserServiceInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ValidationServiceInterface $validationService,
        private readonly LeagueServiceInterface $leagueService,
    ) {
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function editUser(UserInterface $user, UserEditDTO $userEditDTO): UserInterface
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

        return $this->userRepository->save($user, flush: true);
    }

    /**
     * @throws ValidationException
     */
    public function registerUser(NewUserDTO $userDTO): UserInterface
    {
        $this->validationService->validate($userDTO);
        $user = new User();
        $user->setEmail($userDTO->getEmail());
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userDTO->getPassword()
            )
        );
        $user->setRoles(['ROLE_USER']);
        $this->validationService->validate($user);
        
        return $this->userRepository->save($user, flush: true);
    }

}