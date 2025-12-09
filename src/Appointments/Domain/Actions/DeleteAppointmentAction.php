<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Carbon\CarbonImmutable;
use Lightit\Appointments\Domain\Models\Appointment;

class DeleteAppointmentAction
{
    public function execute(Appointment $appointment): Appointment
    {
        $appointment->deleted_at = CarbonImmutable::now();
        $appointment->saveOrFail();

        return $appointment;
    }
}
