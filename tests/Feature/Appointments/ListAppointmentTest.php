<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Database\Factories\UserFactory;
use function Pest\Laravel\actingAs;

describe('appointments', function (): void {
    it('can list appointments successfully', function (): void {
        $user = UserFactory::new()->createOne();
        AppointmentFactory::new()->for($user)->createMany(5);
        $response = actingAs($user, 'api')->getJson(url("/api/users/$user->id/appointments"));
        $response->assertJsonCount(5);
    });
});
