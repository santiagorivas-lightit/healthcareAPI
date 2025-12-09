<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinics\Domain\Models\Clinic;

class AssignClinicToDoctorRequest extends FormRequest
{
    public const string CLINIC_ID = 'clinic_id';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::CLINIC_ID => ['required', Rule::exists(Clinic::class, 'id')],
        ];
    }

    public function getClinicId(): int
    {
        return $this->integer(self::CLINIC_ID);
    }
}
