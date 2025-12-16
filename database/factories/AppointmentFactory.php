<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array{

        $starts = fake()->dateTimeBetween('tomorrow', '+1 day');
        $ends = (clone $starts)->modify('+30 minutes');

        return [
            'user_id' => UserFactory::new(),
            'doctor_id' => DoctorFactory::new(),
            'clinic_id' => ClinicFactory::new(),
            'starts_at' => CarbonImmutable::parse($starts),
            'ends_at' => CarbonImmutable::parse($ends),
        ];
    }

    public function forUser(User $user): self
    {
        return $this->for($user, 'user');
    }

}
