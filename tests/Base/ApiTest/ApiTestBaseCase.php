<?php

namespace App\Tests\Base\ApiTest;


use App\Factory\UserFactory;
use App\Tests\Trait\UserTestConstantsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestBaseCase extends DatabaseDependantApiTestCase
{
    use UserTestConstantsTrait;
    protected function setUp(): void
    {
        parent::setUp();
    }
    protected function createAuthenticatedClient(
        string $email = self::CORRECT_TEST_EMAIL,
        string $password = self::CORRECT_TEST_PASSWORD
    ): KernelBrowser
    {
        UserFactory::createOne([
            'email' => $email,
            'password' => $password,
        ]);
        $this->client->jsonRequest(
            'POST',
            '/api/login_check',
            [
                'username' => $email,
                'password' => $password,
            ]
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $this->client;
    }

}