<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Exceptions;

use Illuminate\Http\Response;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class EmptyUserException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = Response::HTTP_NOT_FOUND;

    /**
     * An error code.
     */
    protected string $errorCode = 'You can not process an empty user';
}
