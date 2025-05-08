<?php

namespace App\Tests\Service;

use App\Service\Interface\RegionResolver\RegionResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegionResolverTest extends KernelTestCase
{
    private RegionResolverInterface $regionResolver;
    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->regionResolver = $container->get(RegionResolverInterface::class);
    }

    public function testRegionNameIsResolvedCorrectly(): void
    {
        $regionName = 'us';
        $regionName2 = 'uk';

        $resolvedName = $this->regionResolver->resolve($regionName);
        $resolvedName2 = $this->regionResolver->resolve($regionName2);

        $this->assertEquals('USA', $resolvedName);
        $this->assertEquals('United Kingdom', $resolvedName2);
    }
}
