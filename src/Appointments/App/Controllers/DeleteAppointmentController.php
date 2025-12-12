<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Appointments\Domain\Models\Appointment;

final class DeleteAppointmentController
{
    public function __invoke(Appointment $appointment): JsonResponse
    {
        $appointment->deleteOrFail();

        return AppointmentResource::make($appointment)
            ->response();
    }
}
