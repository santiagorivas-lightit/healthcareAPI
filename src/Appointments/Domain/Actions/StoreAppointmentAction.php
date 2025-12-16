<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Query\Builder;
use Lightit\Appointments\App\Exceptions\DoctorNotAssignedToSelectedClinicException;
use Lightit\Appointments\App\Exceptions\OverlappingAppointmentsException;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDTO;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;

class StoreAppointmentAction
{
    public function execute(AppointmentDTO $appointmentDto): Appointment
    {
        $appointment = new Appointment();

        if (! $this->doctorWorksAtSelectedClinic($appointmentDto)) {
            throw new DoctorNotAssignedToSelectedClinicException();
        }

        if ($this->overlappingAppointmentsExist($appointmentDto)) {
            throw new OverlappingAppointmentsException();
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
        $doctor = Doctor::query()->findOrFail($appointmentDto->doctorId);

        return $doctor->clinics()
            ->where('clinic_id', $appointmentDto->clinicId)
            ->exists();
    }

    private function overlappingAppointmentsExist(AppointmentDTO $appointmentDto): bool
    {
        $date = CarbonImmutable::parse($appointmentDto->startsAt)->toDateString();

        return Appointment::query()
            ->whereDate('starts_at', '=', $date)
            ->where('starts_at', '<', $appointmentDto->endsAt)
            ->where('ends_at', '>', $appointmentDto->startsAt)
            ->where(function (Builder $query) use ($appointmentDto): void {
                $query->where('doctor_id', $appointmentDto->doctorId)
                    ->orWhere('user_id', $appointmentDto->userId);
            })
            ->exists();
    }
}
