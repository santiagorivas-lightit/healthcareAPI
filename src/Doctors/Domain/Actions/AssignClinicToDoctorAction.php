<?php

declare(strict_types=1);

use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Shared\App\Exceptions\Http\ConflictException;

class assignClinicToDoctorAction
{
    public function execute(Doctor $doctor, int $clinicId): Doctor
    {
        if ($doctor->clinics()->where('id', $clinic_id)->exists()) {
            throw new ConflictException();
        }
        
        $doctor->clinics()->attach($clinicId);

        return $doctor;
    }
}
