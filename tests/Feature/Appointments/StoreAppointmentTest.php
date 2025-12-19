<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;
use Tests\RequestFactories\StoreAppointmentRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

describe('appointments', function (): void {
    it('can create an appointment successfully', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $doctor->clinics()->sync($clinic);
        $starts = CarbonImmutable::now();
        $data = [
            'doctorId' => $doctor->id,
            'clinicId' => $clinic->id,
            'startsAt' => $starts,
            'endsAt' => $starts->addHour(),
        ];
        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

        $response->assertCreated();

        assertDatabaseHas('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $user->id,
        ]);
    });

    it(
        'can not create an appointment with unassigned doctor to selected clinic',
        function (): void {
            $data = StoreAppointmentRequestFactory::new()->create();
            $user = UserFactory::new()->createOne();

            /** @var Doctor $doctor**/
            $doctor = DoctorFactory::new()->createOne();
            $data['doctorId'] = $doctor->id;

            $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

            $response->assertStatus(409);

            assertDatabaseMissing('appointments', [
                'doctor_id' => $data['doctorId'],
                'user_id' => $user->id,
            ]);
        }
    );

    it(
        'can not create an appointment that overlaps with an existing doctor schedule',
        function (): void {
            $originalAppointment = StoreAppointmentRequestFactory::new()->create();
            $userOriginal = UserFactory::new()->createOne();

            actingAs($userOriginal, 'api')->postJson(
                url("/api/users/$userOriginal->id/appointments"),
                $originalAppointment
            );

            $data = $originalAppointment;

            /** @var User $user**/
            $user = UserFactory::new()->createOne();
            $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

            $response->assertStatus(409);

            assertDatabaseMissing('appointments', [
                'user_id' => $user->id,
            ]);
        }
    );

    it('can not create an appointment that overlaps another of the same user', function (): void {
        $originalAppointment = StoreAppointmentRequestFactory::new()->create();
        $user = UserFactory::new()->createOne();

        actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $originalAppointment);

        $data = $originalAppointment;
        /** @var Doctor $doctor**/
        $doctor = DoctorFactory::new()->createOne();
        $data['doctorId'] = $doctor->id;


        $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

        $response->assertStatus(409);

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
        ]);
    });

    it(
        'can create an appointment with same schedule for different doctor and user',
        function (): void {
            $doctor = DoctorFactory::new()->createOne();
            $clinic = ClinicFactory::new()->createOne();
            $doctor->clinics()->sync($clinic);
            $starts = CarbonImmutable::now();
            $originalAppointment = [
                'doctorId' => $doctor->id,
                'clinicId' => $clinic->id,
                'startsAt' => $starts,
                'endsAt' => $starts->addHour(),
            ];
            $userOriginal = UserFactory::new()->createOne();

            actingAs($userOriginal, 'api')->postJson(
                url("/api/users/$userOriginal->id/appointments"),
                $originalAppointment
            );

            $data = $originalAppointment;
            $newDoctor = DoctorFactory::new()->createOne();
            $data['doctorId'] = $newDoctor->id;
            $newClinic = ClinicFactory::new()->createOne();
            $newDoctor->clinics()->sync($newClinic->id);
            $data['clinicId'] = $newClinic->id;

            $user = UserFactory::new()->createOne();
            $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

            $response->assertCreated();

            assertDatabaseHas('appointments', [
                'doctor_id' => $data['doctorId'],
                'user_id' => $user->id,
            ]);
        }
    );

    it('appointment cannot be scheduled in the past', function (): void {
        $data = StoreAppointmentRequestFactory::new()->create();

        $starts = fake()->dateTimeBetween('1800-01-01', '1800-12-31');
        $ends = (clone $starts)->modify('+30 minutes');

        $data['startsAt'] = CarbonImmutable::parse($starts);
        $data['endsAt'] = CarbonImmutable::parse($ends);

        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

        $response->assertUnprocessable();

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $user->id,
        ]);
    });

    it('appointment ends_at must be after starts_at', function (): void {
        $data = StoreAppointmentRequestFactory::new()->create();

        $starts = fake()->dateTimeBetween('1800-01-01', '1800-12-31');
        $ends = (clone $starts)->modify('+30 minutes');

        $data['startsAt'] = CarbonImmutable::parse($ends);
        $data['endsAt'] = CarbonImmutable::parse($starts);

        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);

        $response->assertUnprocessable();

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $user->id,
        ]);
    });

    it('user can have multiple appointments, but not overlapping ones', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $doctor->clinics()->sync($clinic);
        $starts = CarbonImmutable::now();
        $originalAppointment = [
            'doctorId' => $doctor->id,
            'clinicId' => $clinic->id,
            'startsAt' => $starts,
            'endsAt' => $starts->addHour(),
        ];
        $user = UserFactory::new()->createOne();

        $responseOriginal = actingAs($user, 'api')->postJson(
            url("/api/users/$user->id/appointments"),
            $originalAppointment
        );
        $responseOriginal->assertCreated();

        $data = $originalAppointment;



        $data['startsAt'] = $originalAppointment['startsAt']->addDay();
        $data['endsAt'] = $originalAppointment['endsAt']->addDay();

        $response = actingAs($user, 'api')->postJson(url("/api/users/$user->id/appointments"), $data);
        $response->assertCreated();

        assertDatabaseHas('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $user->id,
        ]);
    });
});
