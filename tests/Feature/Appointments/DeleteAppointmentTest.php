<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Lightit\Appointments\Domain\Models\Appointment;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;

describe('appointments', function (): void {
    it('deletes an appointment and returns a successful response', function (): void {
        /** @var Appointment $appointment **/
        $appointment = AppointmentFactory::new()->createOne();

        $appointmentId = $appointment->id;
        deleteJson(url("/api/appointments/$appointmentId"));

        assertDatabaseMissing('appointments', [
            'doctor_id' => $appointment['doctorId'],
            'user_id' => $appointment['userId'],
        ]);
    });
});
