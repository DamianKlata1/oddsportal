<?php

namespace App\Service\Interface\Outcome;

use App\Entity\Outcome;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Enum\PriceFormat;
use App\DTO\Outcome\OutcomeDTO;
use App\Entity\Interface\OutcomeInterface;
use Doctrine\Common\Collections\Collection;
use App\Service\Interface\Entity\EntityServiceInterface;
/**
 * @extends EntityServiceInterface<OutcomeInterface>
 */
interface OutcomeServiceInterface extends EntityServiceInterface
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
