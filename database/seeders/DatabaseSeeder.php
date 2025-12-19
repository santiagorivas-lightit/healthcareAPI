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
        $users = UserFactory::new()->createMany(5);
        $clinics = ClinicFactory::new()->createMany(5);
        $doctors = DoctorFactory::new()->withClinics($clinics)->createMany(5);
        AppointmentFactory::new()->recycle($clinics, $doctors, $users)->createMany(5);
    }
}
