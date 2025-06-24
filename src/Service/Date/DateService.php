<?php

namespace App\Service\Date;

use DateTimeImmutable;
use App\Enum\DateFilterKeyword;
use App\Service\Interface\DateService\DateServiceInterface;

class DateService implements DateServiceInterface
{
    public function calculateDateWindow(?DateFilterKeyword $dateKeyword): array
    {
        $windowStart = null;
        $windowEnd = null;
        $now = new DateTimeImmutable();

        if ($dateKeyword === null) {
            return ['start' => null, 'end' => null];
        }

        switch ($dateKeyword) {
            case DateFilterKeyword::TODAY:
                $windowStart = $now;
                $windowEnd = $now->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::TOMORROW:
                $windowStart = $now->modify('+1 day')->setTime(0, 0, 0);
                $windowEnd = $now->modify('+1 day')->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::THIS_WEEK:

                $windowStart = $now;
                $windowEnd = $now->modify('sunday this week')->setTime(23, 59, 59, 999999);
                break;
            case DateFilterKeyword::NEXT_7_DAYS:
                $windowStart = $now;
                $windowEnd = $now->modify('+6 days')->setTime(23, 59, 59, 999999);
                break;

        }

        return ['start' => $windowStart, 'end' => $windowEnd];
    }

}
