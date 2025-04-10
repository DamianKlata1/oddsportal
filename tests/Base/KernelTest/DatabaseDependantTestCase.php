<?php
namespace App\Tests\Base\KernelTest;

use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class DatabaseDependantTestCase extends KernelTestCase
{
    use ResetDatabase;
    protected function setUp(): void
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);
    }
}