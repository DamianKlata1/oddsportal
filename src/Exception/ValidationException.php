<?php

namespace App\Exception;

use Throwable;

class ValidationException extends \Exception
{
    public function __construct(string $message = "validation failed", int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}