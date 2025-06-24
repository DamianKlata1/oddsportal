<?php

namespace App\Exception;

use Throwable;

class InvalidOddsDataException extends \Exception
{
    public function __construct(string $message = "Invalid odds data received", int $code = 502, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
