<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Requests\StoreAppointmentRequest;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Appointments\Domain\Actions\StoreAppointmentAction;
use Lightit\Users\Domain\Models\User;

final class StoreAppointmentController
{
    public function __invoke(
        User $user,
        StoreAppointmentRequest $request,
        StoreAppointmentAction $action,
    ): JsonResponse {
        $appointment = $action->execute($user, $request->toDto());

        return AppointmentResource::make($appointment)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
