<?php

namespace App\Tests\ExternalApi\ThesportsdbApi;

use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ThesportsdbApiSportLogoPathResolverTest extends KernelTestCase
{
    protected string $thesportsdbIconsUrl;
    protected SportLogoPathResolverInterface $logoPathResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->logoPathResolver = $container->get(SportLogoPathResolverInterface::class);
        $this->thesportsdbIconsUrl = $container->getParameter('thesportsdb_icons_url');
    }

    public function testPathIsResolvedCorrectly()
    {
        $sportName = 'soccer';
        $expectedPath = "{$this->thesportsdbIconsUrl}/sports/soccer.png";

        $resolvedPath = $this->logoPathResolver->resolve($sportName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
    public function testPathIsResolverCorrectlyForNameWithSpaces()
    {
        $sportName = "american football";
        $expectedPath = "{$this->thesportsdbIconsUrl}/sports/americanfootball.png";

        $resolvedPath = $this->logoPathResolver->resolve($sportName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
    public function testPathIsResolvedCorrectlyForFightingSports()
    {
        $sportName = "mixed martial arts";
        $sportName2 = "boxing";
        $expectedPath = "{$this->thesportsdbIconsUrl}/sports/fighting.png";

        $resolvedPath = $this->logoPathResolver->resolve($sportName);
        $resolvedPath2 = $this->logoPathResolver->resolve($sportName2);

        $this->assertEquals($expectedPath, $resolvedPath);
        $this->assertEquals($expectedPath, $resolvedPath2);
    }
    public function testPathIsResolverCorrectlyForPolitics()
    {
        $sportName = "politics";
        $expectedPath = "{$this->thesportsdbIconsUrl}/sports/gambling.png";

        $resolvedPath = $this->logoPathResolver->resolve($sportName);

        $this->assertEquals($expectedPath, $resolvedPath);
    }
}

