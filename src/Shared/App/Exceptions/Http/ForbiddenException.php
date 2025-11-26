<?php

declare(strict_types=1);

namespace Lightit\Shared\App\Exceptions\Http;

class ForbiddenException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 403;

    /**
     * An error code.
     */
    protected string $errorCode = 'forbidden';
}
