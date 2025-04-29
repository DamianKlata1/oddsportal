<?php

namespace App\Tests\ExternalApi;

use App\DTO\ExternalApi\OddsApi\OddsApiSportsDataDTO;
use App\ExternalApi\Interface\OddsApi\OddsApiRegionResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OddsApiRegionResolverTest extends KernelTestCase
{
    protected OddsApiRegionResolverInterface $oddsApiRegionResolver;
    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        $this->oddsApiRegionResolver = $container->get(OddsApiRegionResolverInterface::class);
    }
    public function testRegionNameIsResolvedCorrectly(): void
    {
        $sportsDataDto = new OddsApiSportsDataDTO(
            'poland_sport_name_league_name',
            'sport name',
            'league name',
            'polish league',
            true,
            false
        );
        $regionName = $this->oddsApiRegionResolver->resolveRegionName($sportsDataDto);
        $this->assertEquals('Poland', $regionName);
    }
    public function testOutrightsRegionNameIsResolvedCorrectly(): void
    {
        $sportsDataDto = new OddsApiSportsDataDTO(
            'default_sport_name_league_name',
            'sport name',
            'league name',
            'league',
            false,
            true
        );
        $regionName = $this->oddsApiRegionResolver->resolveRegionName($sportsDataDto);
        $this->assertEquals('Outrights', $regionName);
    }
    public function testDefaultRegionNameIsResolvedCorrectly(): void
    {
        $sportsDataDto = new OddsApiSportsDataDTO(
            'default_sport_name_league_name',
            'sport name',
            'league name',
            'league',
            false,
            false
        );
        $regionName = $this->oddsApiRegionResolver->resolveRegionName($sportsDataDto);
        $this->assertEquals('Default', $regionName);
    }

}
