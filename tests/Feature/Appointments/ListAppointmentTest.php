<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use function Pest\Laravel\getJson;

describe('appointments', function (): void {
    it('can list appointments successfully', function (): void {
        AppointmentFactory::new()->createMany(5);

        getJson(url('/api/appointments'))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    });
});
