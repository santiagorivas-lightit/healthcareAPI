<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Lightit\Doctors\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;
use Tests\RequestFactories\StoreAppointmentRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

describe('appointments', function (): void {
    it(description: 'can create an appointment successfully', closure: function (): void {
        $data = StoreAppointmentRequestFactory::new()->create();

        $response = postJson(url('/api/appointments'), $data);

        $response->assertCreated();

        assertDatabaseHas('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $data['userId'],
        ]);
    });

    it(
        description: 'can not create an appointment with unassigned doctor to selected clinic',
        closure: function (): void {
            $data = StoreAppointmentRequestFactory::new()->create();

            /** @var Doctor $doctor**/
            $doctor = DoctorFactory::new()->createOne();
            $data['doctorId'] = $doctor->id;

            $response = postJson(url('/api/appointments'), $data);

            $response->assertStatus(409);

            assertDatabaseMissing('appointments', [
                'doctor_id' => $data['doctorId'],
                'user_id' => $data['userId'],
            ]);
        }
    );

    it(
        description: 'can not create an appointment that overlaps with an existing doctor schedule',
        closure: function (): void {
            $originalAppointment = StoreAppointmentRequestFactory::new()->create();
            postJson(url('/api/appointments'), $originalAppointment);

            $data = $originalAppointment;

            /** @var User $user**/
            $user = UserFactory::new()->createOne();
            $data['userId'] = $user->id;

            $response = postJson(url('/api/appointments'), $data);

            $response->assertStatus(409);

            assertDatabaseMissing('appointments', [
                'user_id' => $data['userId'],
            ]);
        }
    );

    it(description: 'can not create an appointment that overlaps another of the same user', closure: function (): void {
        $originalAppointment = StoreAppointmentRequestFactory::new()->create();
        postJson(url('/api/appointments'), $originalAppointment);

        $data = $originalAppointment;
        /** @var Doctor $doctor**/
        $doctor = DoctorFactory::new()->createOne();
        $data['doctorId'] = $doctor->id;


        $response = postJson(url('/api/appointments'), $data);

        $response->assertStatus(409);

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
        ]);
    });

    it(
        description: 'can create an appointment with same schedule for different doctor and user',
        closure: function (): void {
            $originalAppointment = StoreAppointmentRequestFactory::new()->create();
            postJson(url('/api/appointments'), $originalAppointment);

            $data = StoreAppointmentRequestFactory::new()->create();
            $data['startsAt'] = $originalAppointment['startsAt'];
            $data['endsAt'] = $originalAppointment['endsAt'];

            $response = postJson(url('/api/appointments'), $data);

            $response->assertCreated();

            assertDatabaseHas('appointments', [
                'doctor_id' => $data['doctorId'],
                'user_id' => $data['userId'],
            ]);
        }
    );

    it(description: 'appointment cannot be scheduled in the past', closure: function (): void {
        $data = StoreAppointmentRequestFactory::new()->create();

        $starts = fake()->dateTimeBetween('1800-01-01', '1800-12-31');
        $ends = (clone $starts)->modify('+30 minutes');

        $data['startsAt'] = CarbonImmutable::parse($starts);
        $data['endsAt'] = CarbonImmutable::parse($ends);

        $response = postJson(url('/api/appointments'), $data);

        $response->assertUnprocessable();

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $data['userId'],
        ]);
    });

    it(description: 'appointment ends_at must be after starts_at', closure: function (): void {
        $data = StoreAppointmentRequestFactory::new()->create();

        $starts = fake()->dateTimeBetween('1800-01-01', '1800-12-31');
        $ends = (clone $starts)->modify('+30 minutes');

        $data['startsAt'] = CarbonImmutable::parse($ends);
        $data['endsAt'] = CarbonImmutable::parse($starts);

        $response = postJson(url('/api/appointments'), $data);

        $response->assertUnprocessable();

        assertDatabaseMissing('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $data['userId'],
        ]);
    });

    it(description: 'user can have multiple appointments, but not overlapping ones', closure: function (): void {
        $originalAppointment = StoreAppointmentRequestFactory::new()->create();
        $responseOriginal = postJson(url('/api/appointments'), $originalAppointment);
        $responseOriginal->assertCreated();

        $data = $originalAppointment;

        /** @var string $startsAtString */
        $startsAtString = $originalAppointment['startsAt'];

        /** @var string $endsAtString */
        $endsAtString = $originalAppointment['endsAt'];

        $data['startsAt'] = CarbonImmutable::parse($startsAtString)->addDay();
        $data['endsAt'] = CarbonImmutable::parse($endsAtString)->addDay();

        $response = postJson(url('/api/appointments'), $data);
        $response->assertCreated();

        assertDatabaseHas('appointments', [
            'doctor_id' => $data['doctorId'],
            'user_id' => $data['userId'],
        ]);
    });
});
