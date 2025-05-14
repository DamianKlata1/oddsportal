<?php

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

class FetchFailedException extends \Exception 
{
    public function __construct(string $message = "Fetch failed", int $code = Response::HTTP_BAD_GATEWAY, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    
}