<?php

namespace App\Tests\Service;

use App\DTO\User\NewUserDTO;
use App\Exception\ValidationException;
use App\Tests\Base\KernelTest\UserTestBaseCase;
use Symfony\Component\Security\Core\User\UserInterface;

class RegisterUserTest extends UserTestBaseCase
{

    public function testRegisterUserCreatesUserSuccessfully(): void
    {
        $userDTO = new NewUserDTO(self::CORRECT_TEST_EMAIL, self::CORRECT_TEST_PASSWORD);


        $user = $this->userService->registerUser($userDTO);

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertNotNull($user->getId());
        $this->assertSame($userDTO->getEmail(), $user->getEmail());

        $savedUser = $this->userRepository->find($user->getId());
        $this->assertNotNull($savedUser);
        $this->assertSame($userDTO->getEmail(), $savedUser->getEmail());
    }

    public function testRegisterUserFailsWithInvalidEmail(): void
    {
        $userDTO = new NewUserDTO(self::INCORRECT_TEST_EMAIL, self::CORRECT_TEST_PASSWORD);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value is not a valid email address.');
        $this->userService->registerUser($userDTO);
    }

    public function testRegisterUserFailsWithInvalidPassword(): void
    {
        $userDTO = new NewUserDTO(self::CORRECT_TEST_EMAIL, self::TOO_SHORT_TEST_PASSWORD);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value is too short. It should have 6 characters or more.');
        $this->userService->registerUser($userDTO);
    }

    public function testRegisterUserFailsWithDuplicateEmail(): void
    {
        $userDTO = new NewUserDTO(self::CORRECT_TEST_EMAIL, self::CORRECT_TEST_PASSWORD);
        $this->userService->registerUser($userDTO);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('There is already an account with this email');
        $this->userService->registerUser($userDTO);
    }

    public function testRegisterUserFailsWithEmptyEmail(): void
    {
        $userDTO = new NewUserDTO('', self::CORRECT_TEST_PASSWORD);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value should not be blank.');
        $this->userService->registerUser($userDTO);
    }

    public function testRegisterUserFailsWithEmptyPassword(): void
    {
        $userDTO = new NewUserDTO(self::CORRECT_TEST_EMAIL, '');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value should not be blank.');
        $this->userService->registerUser($userDTO);
    }
}
