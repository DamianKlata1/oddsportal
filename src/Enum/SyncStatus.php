<?php

namespace App\Enum;

use App\Enum\Trait\Enumerable;

enum SyncStatus: string
{
    use Enumerable;
    case REQUIRED = "required";
    case SKIPPED = "skipped";
}
