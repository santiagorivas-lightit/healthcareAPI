<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\Actions;

use Lightit\Doctors\App\Exceptions\ClinicAlreadyAssignToDoctorException;
use Lightit\Doctors\Domain\Models\Doctor;

class AssignClinicToDoctorAction
{
    public function execute(Doctor $doctor, int $clinic_id): void
    {
        if ($doctor->clinics()->wherePivot('clinic_id', $clinic_id)->exists()) {
            throw new ClinicAlreadyAssignToDoctorException();
        }

        $doctor->clinics()->attach($clinic_id);
    }
}
