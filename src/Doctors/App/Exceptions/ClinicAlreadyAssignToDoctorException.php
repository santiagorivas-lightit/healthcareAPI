<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Exceptions;

use Lightit\Shared\App\Exceptions\Http\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClinicAlreadyAssignToDoctorException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = JsonResponse::HTTP_CONFLICT;

    /**
     * An error code.
     */
    protected string $errorCode = 'This doctor was already assigned to the clinic';
}
