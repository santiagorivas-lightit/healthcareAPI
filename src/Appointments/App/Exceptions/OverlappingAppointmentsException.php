<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Exceptions;

use Illuminate\Http\Response;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class OverlappingAppointmentsException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = Response::HTTP_CONFLICT;

    /**
     * An error code.
     */
    protected string $errorCode = 'The appointment can not be overlapped';
}
