<?php

namespace Lightit\Appointments\Domain\Actions;

use Illuminate\Support\Carbon;
use Lightit\Appointments\Domain\Models\Appointment;

class DeleteAppointmentAction
{
    public function execute(Appointment $appointment): Appointment
    {
        $appointment->deleted_at = Carbon::now();
        $appointment->saveOrFail();
        return $appointment;
    }
}

