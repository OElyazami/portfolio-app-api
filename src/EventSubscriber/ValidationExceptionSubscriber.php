<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UnprocessableEntityHttpException) {
            $previous = $exception->getPrevious();
            if ($previous instanceof ValidationFailedException) {
                $response = $this->createValidationErrorResponse($previous);
                $event->setResponse($response);
            }
        }

        if ($exception instanceof ValidationFailedException) {
            $response = $this->createValidationErrorResponse($exception);
            $event->setResponse($response);
        }
    }

    public function createValidationErrorResponse(ValidationFailedException $exception)
    {

        $violations = $exception->getViolations();

        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
                'invalidValue' => $violation->getInvalidValue(),
            ];
        }

        return new JsonResponse([
            'status' => 'error',
            'code' => 422,
            'message' => 'Validation failed',
            'errors' => $errors,
            'timestamp' => (new \DateTime())->format('c'),
        ], 422);
    }
}
