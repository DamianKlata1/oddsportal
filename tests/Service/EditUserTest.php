<?php

namespace App\Tests\Service;

use App\DTO\User\UserEditDTO;
use App\Exception\ValidationException;
use App\Factory\UserFactory;
use App\Tests\Base\KernelTest\UserTestBaseCase;

class EditUserTest extends UserTestBaseCase
{
    private $userFromRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $userFromFactory = UserFactory::createOne(
            ['email' => self::CORRECT_TEST_EMAIL, 'password' => self::CORRECT_TEST_PASSWORD]
        )->_real();

        // Need to take the user from repository because #[UniqueEntity] constraint
        $this->userFromRepository = $this->userRepository->find($userFromFactory->getId());
    }

    public function testEditUserModifyUserEmailSuccessfully(): void
    {
        $userEditDTO = new UserEditDTO(self::CORRECT_TEST_PASSWORD, self::CORRECT_EDIT_EMAIL);

        $editedUser = $this->userService->editUser($this->userFromRepository, $userEditDTO);

        $this->assertSame(self::CORRECT_EDIT_EMAIL, $editedUser->getEmail());
    }

    public function testEditUserModifyUserPasswordSuccessfully(): void
    {
        $userEditDTO = new UserEditDTO(current_password: self::CORRECT_TEST_PASSWORD, password: self::CORRECT_EDIT_PASSWORD);
        $editedUser = $this->userService->editUser($this->userFromRepository, $userEditDTO);

        $this->assertTrue($this->passwordHasher->isPasswordValid($editedUser, self::CORRECT_EDIT_PASSWORD));
    }

    public function testEditUserFailsWithWrongCurrentPassword(): void
    {
        $userEditDTO = new UserEditDTO(current_password: 'wrong-password', password: 'irrelevant');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Current password is invalid.');
        $this->userService->editUser($this->userFromRepository, $userEditDTO);
    }

    public function testEditUserFailsWithEmptyCurrentPassword(): void
    {
        $userEditDTO = new UserEditDTO(current_password: '', password: self::CORRECT_TEST_PASSWORD);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value should not be blank.');
        $this->userService->editUser($this->userFromRepository, $userEditDTO);
    }

    public function testEditUserFailsWithInvalidEmail(): void
    {

        $userEditDTO = new UserEditDTO(current_password: self::CORRECT_TEST_PASSWORD, email: self::INCORRECT_TEST_EMAIL);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value is not a valid email address.');
        $this->userService->editUser($this->userFromRepository, $userEditDTO);
    }

    public function testEditUserFailsWithTooShortPassword(): void
    {

        $userEditDTO = new UserEditDTO(current_password: self::CORRECT_TEST_PASSWORD, password: self::TOO_SHORT_TEST_PASSWORD);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This value is too short. It should have 6 characters or more.');
        $this->userService->editUser($this->userFromRepository, $userEditDTO);
    }

    public function testEditUserFailsWithDuplicateEmail(): void
    {
        $user2 = UserFactory::createOne(
            ['email' => self::CORRECT_EDIT_EMAIL, 'password' => self::CORRECT_TEST_PASSWORD]
        )->_real();
        $userEditDTO = new UserEditDTO(current_password: self::CORRECT_TEST_PASSWORD, email: self::CORRECT_EDIT_EMAIL);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('There is already an account with this email');
        $this->userService->editUser($this->userFromRepository, $userEditDTO);
    }



}
