<?php

namespace App\EventSubscriber;

use App\Response\ErrorResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    const VALIDATION_FAILED_MESSAGE = 'VALIDATION_FAILED';
    const CODE = Response::HTTP_UNPROCESSABLE_ENTITY;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 100]
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
                $event->stopPropagation();
            }
        }

        if ($exception instanceof ValidationFailedException) {
            $response = $this->createValidationErrorResponse($exception);
            $event->setResponse($response);
            $event->stopPropagation();
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

        return new JsonResponse(
            (new ErrorResponse())
                ->setMessage(self::VALIDATION_FAILED_MESSAGE)
                ->setErrors($errors)
                ->setCode(self::CODE)
                ->getArrayFormat()
        );
    }
}
