<?php

namespace App\Tests\ExternalApi\ThesportsdbApi;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;

class ThesportsdbApiRegionLogoPathResolverTest extends KernelTestCase
{
    protected RegionLogoPathResolverInterface $logoPathResolver;
    protected string $thesportsdbFlagsUrl;
    protected string $worldLogoUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->logoPathResolver = $container->get(RegionLogoPathResolverInterface::class);
        $this->thesportsdbFlagsUrl = $container->getParameter('thesportsdb_flags_url');
        $this->worldLogoUrl = $container->getParameter('thesportsdb_world_logo_url');
    }

    public function testPathIsResolvedCorrectly()
    {
        $regionName = 'england';
        $expectedPath = "{$this->thesportsdbFlagsUrl}/england.png";

        $resolvedPath = $this->logoPathResolver->resolve($regionName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
    public function testPathIsResolvedCorrectlyForNameWithSpaces()
    {
        $regionName = 'american samoa';
        $expectedPath = "{$this->thesportsdbFlagsUrl}/american-samoa.png";

        $resolvedPath = $this->logoPathResolver->resolve($regionName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
    public function testPathIsResolvedCorrectlyForNamesWithoutOwnUrl()
    {
        $regionName = 'some unknown region';
        $expectedPath = "{$this->thesportsdbFlagsUrl}/world.png";

        $resolvedPath = $this->logoPathResolver->resolve($regionName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
    
}  
