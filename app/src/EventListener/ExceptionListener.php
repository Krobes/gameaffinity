<?php

// src/EventListener/ErrorListener.php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ExceptionListener implements EventSubscriberInterface
{
    private $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        // Obtén el código de estado HTTP de la excepción, o usa 500 si no es válido.
        $statusCode = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : 500;

        // Asegúrate de que el código de estado sea uno válido
        if (!in_array($statusCode, [400, 404, 500])) {
            $statusCode = 500;
        }

        switch ($statusCode) {
            case 400:
                $template = 'error/error-400.html.twig';
                break;
            case 404:
                $template = 'error/error-404.html.twig';
                break;
            case 500:
                $template = 'error/error-500.html.twig';
                break;
            default:
                $template = 'error/error.html.twig';
                break;
        }

        $response = new Response(
            $this->templating->render(
                $template,
                ['exception' => $exception]
            ),
            $statusCode
        );

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}

