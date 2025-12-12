<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Lightit\Appointments\App\Exceptions\AppointmentNotAssignedToUserException;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;

class DeleteAppointmentAction
{
    public function execute(User $user, Appointment $appointment): Appointment
    {
        if (! $this->usersDeleteTheirOwnAppointments($user, $appointment)) {
            throw new AppointmentNotAssignedToUserException();
        }

        $appointment->deleteOrFail();

        return $appointment;
    }

    private function usersDeleteTheirOwnAppointments(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id;
    }
}
