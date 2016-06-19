<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/18/16
 * Time: 5:27 PM
 */

namespace AppBundle\EventListener;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener {
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Return all exception in json format
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new JsonResponse();
        $response->setData(['error' => $exception->getMessage()]);
        $event->setResponse($response);
    }
}