<?php

namespace App\Factory;

use App\Entity\LeagueOddsImportSync;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<LeagueOddsImportSync>
 */
final class LeagueOddsImportSyncFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return LeagueOddsImportSync::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'betRegion' => BetRegionFactory::new(),
            'lastImportedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'league' => LeagueFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OddsDataImportSync $oddsDataImportSync): void {})
        ;
    }
}
