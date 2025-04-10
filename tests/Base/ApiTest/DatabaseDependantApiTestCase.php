<?php
namespace App\Tests\Base\ApiTest;

use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class DatabaseDependantApiTestCase extends WebTestCase
{
    use ResetDatabase;
    protected KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();

        DatabasePrimer::prime(self::$kernel);
    }
}