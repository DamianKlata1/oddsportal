<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse([
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } elseif ($exception instanceof ValidationException) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}