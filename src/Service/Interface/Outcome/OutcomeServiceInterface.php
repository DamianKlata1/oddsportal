<?php

namespace App\Service\Interface\Outcome;

use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Enum\PriceFormat;
use App\DTO\Outcome\OutcomeDTO;
use Doctrine\Common\Collections\Collection;

interface OutcomeServiceInterface
{
    /**
     * @param Collection<int, Outcome> $outcomes
     * @return OutcomeDTO[]
     */
    public function getBestOutcomes(Collection $outcomes, PriceFormat $format): array;
    /**
     * @param Collection<int, Outcome> $outcomes
     * @param MarketType[] $markets
     * @return Collection<int, Outcome>
     */
    public function filterOutcomesByMarketsAndRegion(Collection $outcomes, array $markets, BetRegion $region): Collection;


}
