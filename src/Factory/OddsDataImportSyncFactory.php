<?php

namespace App\Factory;

use App\Entity\OddsDataImportSync;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<OddsDataImportSync>
 */
final class OddsDataImportSyncFactory extends PersistentProxyObjectFactory
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
        return OddsDataImportSync::class;
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
