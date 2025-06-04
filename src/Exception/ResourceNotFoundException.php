<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceNotFoundException extends \Exception
{
    public function __construct(string $message = 'Resource not found', $code = 404)
    {
        parent::__construct($message, code: $code);
    }
}
