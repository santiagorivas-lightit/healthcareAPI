<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\AppointmentFactory;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserFactory::new()->createMany(10);
        $clinics = ClinicFactory::new()->createMany(10);
        $doctors = DoctorFactory::new()->withClinics($clinics)->createMany(10);
        AppointmentFactory::new()->recycle($clinics, $doctors)->createMany(15);
    }
}
