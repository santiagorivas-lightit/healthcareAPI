<?php

declare(strict_types=1);

namespace Lightit\Shared\App\Exceptions\Http;

class ConflictException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 409;

    /**
     * An error code.
     */
    protected string $errorCode = 'conflict';
}
