<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Appointments\Domain\DataTransferObjects\AppointmentDTO;
use Lightit\Clinics\Domain\Models\Clinic;
use Lightit\Doctors\Domain\Models\Doctor;

class StoreAppointmentRequest extends FormRequest
{
    public const string DOCTOR_ID = 'doctorId';

    public const string CLINIC_ID = 'clinicId';

    public const string STARTS_AT = 'startsAt';

    public const string ENDS_AT = 'endsAt';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::CLINIC_ID => ['required', Rule::exists(Clinic::class, 'id')],
            self::DOCTOR_ID => ['required', Rule::exists(Doctor::class, 'id')],
            self::STARTS_AT => ['required', Rule::date()->after(CarbonImmutable::now())],
            self::ENDS_AT => ['required', Rule::date()->after(self::STARTS_AT)],
        ];
    }

    public function toDto(): AppointmentDto
    {
        return new AppointmentDto(
            doctorId: $this->integer(self::DOCTOR_ID),
            clinicId: $this->integer(self::CLINIC_ID),
            startsAt: CarbonImmutable::parse($this->string(self::STARTS_AT)->toString()),
            endsAt: CarbonImmutable::parse($this->string(self::ENDS_AT)->toString()),
        );
    }
}
