<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Clinics\Domain\Models\Clinic;
use Lightit\Doctors\Domain\Models\Doctor;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name()
        ];
    }

    /**
     * @param Collection<int, Clinic> $clinics
     * @return static
     */
    public function withClinics(Collection $clinics): static
    {
        return $this->afterCreating(function (Doctor $doctor) use ($clinics) {
            $doctor->clinics()->attach($clinics);
        });
    }
}
