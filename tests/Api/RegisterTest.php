<?php

namespace App\Tests\Api;

use App\Factory\UserFactory;
use App\Repository\UserRepositoryInterface;
use App\Tests\Base\ApiTest\ApiTestBaseCase;
use App\Tests\Trait\UserTestConstantsTrait;

class RegisterTest extends ApiTestBaseCase
{
    const REGISTER_URL = '/api/register';
    use UserTestConstantsTrait;
    protected UserRepositoryInterface $userRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
    }

    public function testRegisterUserIsSuccessfulWithCorrectCredentials(): void
    {
        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => self::CORRECT_TEST_EMAIL,
            'password' => self::CORRECT_TEST_PASSWORD
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('User registered successfully', $this->client->getResponse()->getContent());

        $user = $this->userRepository->findOneBy(['email' => self::CORRECT_TEST_EMAIL]);
        $this->assertNotNull($user);
    }

    public function testRegisterUserFailsWithInvalidEmail(): void
    {
        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => self::INCORRECT_TEST_EMAIL,
            'password' => self::CORRECT_TEST_PASSWORD
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value is not a valid email address.', $this->client->getResponse()->getContent());
    }

    public function testRegisterUserFailsWithDuplicateEmail(): void
    {
        UserFactory::createOne(['email' => self::CORRECT_TEST_EMAIL]);

        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => self::CORRECT_TEST_EMAIL,
            'password' => self::CORRECT_TEST_PASSWORD
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('There is already an account with this email', $this->client->getResponse()->getContent());
    }
    public function testRegisterUserFailsWithInvalidPassword(): void
    {
        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => self::CORRECT_TEST_EMAIL,
            'password' => self::TOO_SHORT_TEST_PASSWORD
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value is too short. It should have 6 characters or more.', $this->client->getResponse()->getContent());
    }
    public function testRegisterUserFailsWithEmptyEmail(): void
    {
        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => '',
            'password' => self::CORRECT_TEST_PASSWORD
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value should not be blank.', $this->client->getResponse()->getContent());
    }
    public function testRegisterUserFailsWithEmptyPassword(): void
    {
        $this->client->jsonRequest('POST', self::REGISTER_URL, [
            'email' => self::CORRECT_TEST_EMAIL,
            'password' => ''
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('This value should not be blank.', $this->client->getResponse()->getContent());
    }
}
