<?php

namespace App\DataFixtures;

use App\Entity\BetRegion;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BetRegionFixtures extends Fixture implements FixtureGroupInterface
{
    private const REGIONS = ['us', 'us2', 'uk', 'au', 'eu'];
    public static function getGroups(): array
    {
        return ['production-data'];
    }
    public function load(ObjectManager $manager): void
    {
        foreach (self::REGIONS as $regionName) {
            $existingRegion = $manager->getRepository(BetRegion::class)->findOneBy(['name' => $regionName]);

            if (!$existingRegion) {
                $betRegion = new BetRegion();
                $betRegion->setName($regionName);

                $manager->persist($betRegion);
            }
        }

        $manager->flush();
    }
}
