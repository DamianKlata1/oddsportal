<?php

namespace App\Factory;

use App\Entity\Outcome;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Outcome>
 */
final class OutcomeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public static function class(): string
    {
        return Outcome::class;
    }

    protected function defaults(): array
    {
        return [
            'name' => 'Home',
            'price' => 1.5,
            'market' => 'H2H',
            'bookmaker' => BookmakerFactory::new(),
            'event' => EventFactory::new(),
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function(Outcome $outcome) {
            if ($outcome->getEvent() !== null) {
                $outcome->getEvent()->addOutcome($outcome); // ustawienie odwrotnej relacji
            }
        });
    }

}
