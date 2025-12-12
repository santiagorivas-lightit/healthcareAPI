<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Users\Domain\Models\User;

final class ListAppointmentController
{
    public function __invoke(
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        return AppointmentResource::collection($user->appointments())
            ->response();
    }
}
