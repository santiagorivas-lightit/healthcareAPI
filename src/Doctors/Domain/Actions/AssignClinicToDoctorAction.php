<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\Actions;

use Lightit\Doctors\App\Exceptions\ClinicAlreadyAssignToDoctorException;
use Lightit\Doctors\Domain\Models\Doctor;

class AssignClinicToDoctorAction
{
    public function execute(Doctor $doctor, int $clinicId): void
    {
        if ($doctor->clinics()->where('id', $clinicId)->exists()) {
            throw new ClinicAlreadyAssignToDoctorException();
        }

        $doctor->clinics()->attach($clinicId);
    }
}
