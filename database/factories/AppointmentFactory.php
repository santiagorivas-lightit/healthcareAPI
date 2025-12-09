<?php

declare(strict_types=1);

namespace Database\Factories;


use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Appointments\Domain\Models\Appointment;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array{

        $starts = fake()->dateTimeBetween('tomorrow', '+1 day');
        $ends = (clone $starts)->modify('+30 minutes');
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $doctor->clinics()->attach($clinic);

        return [
            'user_id' => UserFactory::new(),
            'doctor_id' => $doctor->id,
            'clinic_id' =>$clinic->id,
            'starts_at' => CarbonImmutable::parse($starts),
            'ends_at' => CarbonImmutable::parse($ends),
        ];
    }

}
