<?php


declare(strict_types=1);

namespace Lightit\Appointments\App\Exceptions;

use Illuminate\Http\Response;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class DoctorNotAssignedToSelectedClinicException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = Response::HTTP_CONFLICT;

    /**
     * An error code.
     */
    protected string $errorCode = 'This doctor does not work on the selected clinic';
}
