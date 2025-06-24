<?php

namespace App\Enum;

enum SyncStatus: string
{
    case REQUIRED = "required";
    case SKIPPED = "skipped";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}
