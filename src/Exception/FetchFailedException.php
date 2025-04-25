<?php

namespace App\Exception;

use Throwable;

class FetchFailedException extends \Exception 
{
    public function __construct(string $message = "Fetch failed", int $code = 502, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    
}