<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Exceptions;

use Lightit\Shared\App\Exceptions\Http\HttpException;

class ClinicAlreadyAssignToDoctorException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 409;

    /**
     * An error code.
     */
    protected string $errorCode = 'This doctor was already assigned to the clinic';
}
