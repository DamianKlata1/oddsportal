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
        $outcomesGroupedByName = $this->groupOutcomesByName($outcomes);
        $bestOutcomes = [];
        foreach ($outcomesGroupedByName as $name => $outcomes) {

            $bestOutcome = $this->calculateBestOutcome($outcomes);
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
    public function filterOutcomesByMarketsAndRegion(Collection $outcomes, array $markets, BetRegion $region): Collection
    {
        return $outcomes->filter(function ($outcome) use ($markets, $region) {
            return $outcome instanceof Outcome
                && in_array(MarketType::fromString($outcome->getMarket()), $markets, true)
                && $outcome->getBookmaker()->getBetRegions()->contains($region);
        });
    }
    
    private function groupOutcomesByName(Collection $outcomes): Collection
    {
        return $outcomes->reduce(
            function (ArrayCollection $carry, Outcome $outcome) {
                if (!$carry->containsKey($outcome->getName())) {
                    $carry->set($outcome->getName(), new ArrayCollection());
                }
                $carry->get($outcome->getName())->add($outcome);
                return $carry;
            },
            new ArrayCollection()
        );
    }

    private function calculateBestOutcome(Collection $outcomes): ?Outcome
    {
        return $outcomes->reduce(
            function (?Outcome $carry, Outcome $item) {
                if ($carry === null || $item->getPrice() > $carry->getPrice()) {
                    return $item;
                }
                return $carry;
            }
        );
    }



}
