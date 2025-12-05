<?php


declare(strict_types=1);

namespace Lightit\Appointments\App\Exceptions;

use Lightit\Shared\App\Exceptions\Http\HttpException;

class doctorNotAssignedToSelectedClinicException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 409; //JsonResponse::HTTP_CONFLICT;

    /**
     * An error code.
     */
    protected string $errorCode = 'This doctor does not work on the selected clinic';
}
