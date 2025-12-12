<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Lightit\Appointments\App\Exceptions\doctorNotAssignedToSelectedClinicException;
use Lightit\Appointments\App\Exceptions\overlappingAppointmensException;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Query\Builder;
use Lightit\Appointments\App\Exceptions\DoctorNotAssignedToSelectedClinicException;
use Lightit\Appointments\App\Exceptions\OverlappingAppointmentsException;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDTO;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;

class StoreAppointmentAction
{
    public function execute(User $user, AppointmentDTO $appointmentDto): Appointment
    {
        $appointment = new Appointment();

        if (! $this->doctorWorksAtSelectedClinic($appointmentDto)) {
            throw new DoctorNotAssignedToSelectedClinicException();
        }

        if ($this->overlappingAppointmentsExist($user, $appointmentDto)) {
            throw new overlappingAppointmensException();
        if ($this->overlappingAppointmentsExist($appointmentDto)) {
            throw new OverlappingAppointmentsException();
        }

        $appointment->user_id = $user->id;
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

    private function overlappingAppointmentsExist(User $user, AppointmentDTO $appointmentDto): bool
    {
        $date = CarbonImmutable::parse($appointmentDto->startsAt)->toDateString();

        return Appointment::query()
            ->whereDate('starts_at', '=', $date)
            ->where('starts_at', '<', $appointmentDto->endsAt)
            ->where('ends_at', '>', $appointmentDto->startsAt)
            ->where(function (Builder $query) use ($user, $appointmentDto): void {
                $query->where('doctor_id', $appointmentDto->doctorId)
                    ->orWhere('user_id', $user->id);
            })
            ->exists();
    }
}
