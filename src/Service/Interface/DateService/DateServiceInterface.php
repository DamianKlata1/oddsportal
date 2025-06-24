<?php

namespace App\Service\Interface\DateService;

use App\Enum\DateFilterKeyword;

interface DateServiceInterface
{
    public function calculateDateWindow(?DateFilterKeyword $dateKeyword): array;
}
