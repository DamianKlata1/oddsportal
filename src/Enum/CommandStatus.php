<?php

namespace App\Enum;

use App\Enum\Trait\Enumerable;

enum CommandStatus: string
{
    use Enumerable;
    case RUNNING = "running";
    case SUCCESS = "success";
    case FAILURE = "failure";
}
