<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignClinicToDoctorRequest extends FormRequest
{
    public const string CLINIC_ID = 'clinicId';
    
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::CLINIC_ID => ['required', Rule::exists(Clinic::class)],
        ];
    }

    public function getClinicId(): int
    {
        return $this->int(self::CLINIC_ID)->toInteger();
    }
}
