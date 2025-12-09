<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Illuminate\Support\Carbon;
use Lightit\Appointments\App\Exceptions\doctorNotAssignedToSelectedClinicException;
use Lightit\Appointments\App\Exceptions\overlappingAppointmensException;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDTO;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;

class StoreAppointmentAction
{
    public function execute(AppointmentDTO $appointmentDto): Appointment
    {
        $appointment = new Appointment();

        if (! $this->doctorWorksAtSelectedClinic($appointmentDto)) {
            throw new doctorNotAssignedToSelectedClinicException();
        }

        if ($this->overlappingAppointmentsExist($appointmentDto)) {
            throw new overlappingAppointmensException();
        }

        $appointment->user_id = $appointmentDto->userId;
        $appointment->doctor_id = $appointmentDto->doctorId;
        $appointment->clinic_id = $appointmentDto->clinicId;
        $appointment->starts_at = $appointmentDto->startsAt;
        $appointment->ends_at = $appointmentDto->endsAt;

        $appointment->saveOrFail();

        return $appointment;
    }

    private function doctorWorksAtSelectedClinic(AppointmentDTO $appointmentDto): bool
    {
        $doctor = \Lightit\Doctors\Domain\Models\Doctor::query()->findOrFail($appointmentDto->doctorId);

        return $doctor->clinics()
            ->where('clinic_id', $appointmentDto->clinicId)
            ->exists();
    }

    private function overlappingAppointmentsExist(AppointmentDTO $appointmentDto): bool
    {
        $date = \Illuminate\Support\Facades\Date::parse($appointmentDto->startsAt)->toDateString();

        return Appointment::query()
            ->whereDate('starts_at', '=', $date)
            ->where('starts_at', '<', $appointmentDto->endsAt)
            ->where('ends_at', '>', $appointmentDto->startsAt)
            ->where(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($appointmentDto): void {
                $query->where('doctor_id', $appointmentDto->doctorId)
                    ->orWhere('user_id', $appointmentDto->userId);
            })
            ->exists();
    }
}
