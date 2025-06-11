<?php

namespace App\EventSubscriber;

use ReflectionClass;
use App\Attribute\HttpCache;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HttpCacheSubscriber implements EventSubscriberInterface
{
    private ?HttpCache $cacheAttribute = null;
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        [$controllerObject, $methodName] = $controller;

        $reflectionMethod = (new ReflectionClass($controllerObject))->getMethod($methodName);
        $attributes = $reflectionMethod->getAttributes(HttpCache::class);

        if ($attributes) {
            $this->cacheAttribute = $attributes[0]->newInstance();
        }
    }
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->cacheAttribute) {
            return;
        }

        $response = $event->getResponse();

        if (!$response->isSuccessful()) {
            return;
        }

        if ($this->cacheAttribute->public) {
            $response->setPublic();
        } else {
            $response->setPrivate();
        }

        if ($this->cacheAttribute->maxage !== null) {
            $response->setMaxAge($this->cacheAttribute->maxage);
        }

        if ($this->cacheAttribute->smaxage !== null) {
            $response->setSharedMaxAge($this->cacheAttribute->smaxage);
        }

        $response->setEtag(md5($response->getContent()));

        if ($response->isNotModified($event->getRequest())) {
        }
    }

}
