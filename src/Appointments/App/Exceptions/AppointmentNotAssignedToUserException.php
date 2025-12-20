<?php


declare(strict_types=1);

namespace Lightit\Appointments\App\Exceptions;

use Lightit\Shared\App\Exceptions\Http\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppointmentNotAssignedToUserException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = JsonResponse::HTTP_FORBIDDEN;

    /**
     * An error code.
     */
    protected string $errorCode = 'Users can only delete their own appointments';
}
