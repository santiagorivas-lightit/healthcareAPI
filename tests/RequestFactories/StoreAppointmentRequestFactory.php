<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Worksome\RequestFactories\RequestFactory;

class StoreAppointmentRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $starts = fake()->dateTimeBetween('tomorrow', '+1 day');
        $ends = (clone $starts)->modify('+30 minutes');
        $clinic = ClinicFactory::new()->createOne();

        return [
            'doctorId' => DoctorFactory::new()->withClinics($clinic),
            'clinicId' => $clinic->id,
            'startsAt' => CarbonImmutable::parse($starts),
            'endsAt' => CarbonImmutable::parse($ends),
        ];
    }
}
