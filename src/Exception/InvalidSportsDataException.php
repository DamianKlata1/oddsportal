<?php

namespace App\Exception;

use Throwable;

class InvalidSportsDataException extends \Exception
{
    public function __construct(string $message = "Invalid sports data received", int $code = 502, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
