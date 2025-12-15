<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;

final class DeleteAppointmentController
{
    public function __invoke(User $user, Appointment $appointment): JsonResponse
    {
        $appointment = $appointment->deleteOrFail();

        return AppointmentResource::make($appointment)
            ->response();
    }
}
