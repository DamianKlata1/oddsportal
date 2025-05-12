<?php

namespace App\Service\Outcome;

use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Enum\PriceFormat;
use App\DTO\Outcome\OutcomeDTO;
use App\DTO\Bookmaker\BookmakerDTO;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Interface\Outcome\OutcomeServiceInterface;
use App\Service\Interface\Outcome\PriceFormatConverterInterface;

class OutcomeService implements OutcomeServiceInterface
{
    public function __construct(
        private readonly PriceFormatConverterInterface $priceFormatConverter,
    ) {
    }
    public function getBestOutcomes(Collection $outcomes, PriceFormat $format = PriceFormat::DECIMAL): array
    {
        $outcomesGroupedByName = $outcomes->reduce(
            function (array $carry, Outcome $outcome) {
                $carry[$outcome->getName()][] = $outcome;
                return $carry;
            },
            []
        );
        $bestOutcomes = [];
        foreach ($outcomesGroupedByName as $name => $outcomes) {
            /**
             * @var Outcome $bestOutcome
             */
            $bestOutcome = max($outcomes, fn($a, $b) => $a->getPrice() <=> $b->getPrice());
            $bestOutcomes[] = new OutcomeDTO(
                id: $bestOutcome->getId(),
                name: $name,
                price: $this->priceFormatConverter->apply($bestOutcome->getPrice(), $format),
                bookmaker: new BookmakerDTO(
                    name: $bestOutcome->getBookmaker()->getName(),
                ),
                lastUpdate: $bestOutcome->getLastUpdate(),
            );
        }
        return $bestOutcomes;
    }
    public function filterOutcomesByMarketAndRegion(Collection $outcomes, MarketType $market, BetRegion $region): Collection
    {
        return $outcomes->filter(function ($outcome) use ($market, $region) {
            return $outcome instanceof Outcome
                && $outcome->getMarket() === $market->toString()
                && $outcome->getBookmaker()->getBetRegions()->contains($region);
        });
    }



}
