<?php

namespace App\Tests\Base\ApiTest;

use App\Tests\Trait\UserTestConstantsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class ApiTestBaseCase extends DatabaseDependantApiTestCase
{
    use UserTestConstantsTrait;

    protected function createAuthenticatedClient(
        string $email = self::CORRECT_TEST_EMAIL,
        string $password = self::CORRECT_TEST_PASSWORD
    ): KernelBrowser
    {
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