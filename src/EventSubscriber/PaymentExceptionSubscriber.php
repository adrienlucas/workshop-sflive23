<?php

namespace App\EventSubscriber;

use App\Message\InvalidPaymentStateException;
use PHPUnit\Util\Json;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PaymentExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        switch(true) {
            case $exception instanceof InvalidPaymentStateException:
                $event->setResponse(new JsonResponse(['error' => 'Invalid payment state'], 400));
                break;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
