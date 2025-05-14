<?php

namespace App\Exception;

use Throwable;

class ImportFailedException extends \Exception 
{
    public function __construct(string $message = "Import failed", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    
}