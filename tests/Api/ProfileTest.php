<?php

namespace App\Tests\Api;

use App\Factory\UserFactory;
use App\Repository\Interface\UserRepositoryInterface;
use App\Tests\Base\ApiTest\ApiTestBaseCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @group profile
 */
class ProfileTest extends ApiTestBaseCase
{
    const PROFILE_URL = '/api/account';
    const EDIT_USER_URL = '/api/account/edit';
    private UserRepositoryInterface $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private string $plainPassword;
    private UserInterface $userFromRepository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->plainPassword = self::CORRECT_TEST_PASSWORD;
        $userFromFactory = UserFactory::createOne(
            ['email' => self::CORRECT_TEST_EMAIL, 'password' => $this->plainPassword]
        )->_real();

        $this->userFromRepository = $this->userRepository->find($userFromFactory->getId());
    }

    public function testGetAccountReturnsCorrectCredentials(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);

        $this->client->jsonRequest('GET', self::PROFILE_URL);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->userFromRepository->getEmail(), $responseData['email']);
        $this->assertEquals($this->userFromRepository->getRoles(), $responseData['roles']);
    }

    public function testEditUserModifyUserEmailSuccessfully(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => $this->plainPassword
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('User edited successfully.', $responseData['message']);

        $user = $this->userRepository->findOneBy(['id' => $this->userFromRepository->getId()]);
        $this->assertEquals(self::CORRECT_EDIT_EMAIL, $user->getEmail());
    }
    public function testEditUserModifyPasswordSuccessfully(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'password' => self::CORRECT_EDIT_PASSWORD,
            'current_password' => $this->plainPassword
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $user = $this->userRepository->findOneBy(['id' => $this->userFromRepository->getId()]);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('User edited successfully.', $responseData['message']);
        $this->assertTrue($this->passwordHasher->isPasswordValid($user, self::CORRECT_EDIT_PASSWORD));
    }
    public function testGetAccountReturns401WhenNotAuthenticated(): void
    {
        $this->client->jsonRequest('GET', self::PROFILE_URL);
        $responseData = json_decode($this->client->getResponse()->getContent(),true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertEquals('JWT Token not found', $responseData['message']);
    }
    public function testEditUserReturns422WhenEmailAlreadyExists(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $userExistingInDb = UserFactory::createOne([
            'email' => self::CORRECT_EDIT_EMAIL,
            'password' => self::CORRECT_TEST_PASSWORD,
        ])->_real();
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => $userExistingInDb->getEmail(),
            'current_password' => $this->plainPassword
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('There is already an account with this email.', $responseData['message']);
    }
    public function testEditUserReturns422WhenCurrentPasswordIsIncorrect(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => self::INCORRECT_TEST_PASSWORD
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('Current password is invalid.', $responseData['message']);
    }
    public function testEditUserReturns422WhenCurrentPasswordIsBlank(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => ''
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value should not be blank.', $responseData['message']);
    }
    public function testEditUserReturns422WhenEmailIsInvalid(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::INCORRECT_TEST_EMAIL,
            'current_password' => $this->plainPassword
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value is not a valid email address.', $responseData['message']);
    }
    public function testEditUserReturns422WhenPasswordIsTooShort(): void
    {
        $this->client = $this->createAuthenticatedClient($this->userFromRepository->getEmail(), $this->plainPassword);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => $this->plainPassword,
            'password' => self::TOO_SHORT_TEST_PASSWORD
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value is too short. It should have 6 characters or more.', $responseData['message']);
    }
}
