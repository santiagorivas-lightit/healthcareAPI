<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Lightit\Appointments\App\Exceptions\{
    doctorNotAssignedToSelectedClinicException,
    overlappingAppointmensException
};
use Illuminate\Support\Carbon;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDTO;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;

class StoreAppointmentAction
{
    public function execute(AppointmentDTO $appointmentDto): Appointment
    {
        $appointment = new Appointment();

        if (! $this->doctorWorksAtSelectedClinic($appointmentDto)) {
            throw new doctorNotAssignedToSelectedClinicException();
        }

        if ($this->overlappingAppointmentsExist($appointmentDto)){
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

    private function doctorWorksAtSelectedClinic(AppointmentDTO $appointmentDto) : bool
    {
        $doctor = Doctor::findOrFail($appointmentDto->doctorId);

        return $doctor->clinics()
            ->where('clinic_id', $appointmentDto->clinicId)
            ->exists();
    }

    /**
     * @param AppointmentDTO $appointmentDto
     * @return bool
     */
    private function overlappingAppointmentsExist(AppointmentDTO $appointmentDto): bool
    {
        $date = Carbon::parse($appointmentDto->startsAt)->toDateString();

        return Appointment::query()
            ->whereDate('starts_at', '=', $date)
            ->where('starts_at', '<', $appointmentDto->endsAt)
            ->where('ends_at', '>', $appointmentDto->startsAt)
            ->where(function ($query) use ($appointmentDto) {
                $query->where('doctor_id', $appointmentDto->doctorId)
                    ->orWhere('user_id', $appointmentDto->userId);
            })
            ->exists();
    }
}
