<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Appointments\App\Resources\AppointmentResource;
use Lightit\Appointments\Domain\Actions\ListAppointmentAction;

final class ListAppointmentController
{
    public function __invoke(
        ListAppointmentAction $action,
    ): JsonResponse {
        $appointments = $action->execute();

        return AppointmentResource::collection($appointments)
            ->response();
    }
}
