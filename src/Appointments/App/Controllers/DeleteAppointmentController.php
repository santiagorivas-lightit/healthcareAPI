<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Appointments\Domain\Actions\DeleteAppointmentAction;
use Lightit\Appointments\Domain\Models\Appointment;

final readonly class DeleteAppointmentController
{
    public function __invoke(Appointment $appointment, DeleteAppointmentAction $action): JsonResponse
    {
        $appointment = $action->execute($appointment);

        return AppointmentResource::make($appointment)
            ->response();
    }
}
