<?php

namespace App\Tests\Controller;

use App\Factory\UserFactory;
use App\Tests\Base\ApiTest\ApiTestBaseCase;

class ProfileTest extends ApiTestBaseCase
{
    const PROFILE_URL = '/api/account';
    const EDIT_USER_URL = '/api/account/edit';
    public function testGetAccountReturnsCorrectCredentials(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->jsonRequest('GET', self::PROFILE_URL);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(self::CORRECT_TEST_EMAIL, $responseData['email']);
        $this->assertEquals('ROLE_USER', $responseData['roles'][0]);
    }

    public function testGetAccountReturns401WhenNotAuthenticated(): void
    {
        $this->client->jsonRequest('GET', self::PROFILE_URL);
        $responseData = json_decode($this->client->getResponse()->getContent(),true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertEquals('JWT Token not found', $responseData['message']);
    }
    public function testEditUserModifyUserEmailSuccessfully(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => self::CORRECT_TEST_PASSWORD
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('User edited successfully.', $responseData['message']);
    }
    public function testEditUserReturns422WhenEmailAlreadyExists(): void
    {
        $this->client = $this->createAuthenticatedClient();
        UserFactory::createOne([
            'email' => self::CORRECT_EDIT_EMAIL,
            'password' => self::CORRECT_TEST_PASSWORD,
        ]);
        $this->client->jsonRequest('PATCH', self::EDIT_USER_URL, [
            'email' => self::CORRECT_EDIT_EMAIL,
            'current_password' => self::CORRECT_TEST_PASSWORD
        ]);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString('There is already an account with this email.', $responseData['message']);
    }


}
