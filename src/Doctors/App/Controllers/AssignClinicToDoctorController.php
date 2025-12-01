<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Doctors\App\Requests\AssignClinicToDoctorRequest;
use Lightit\Doctors\App\Resources\DoctorResource;
use Lightit\Doctors\Domain\Actions\AssignClinicToDoctorAction;

#[Group('Doctors')]
final readonly class AssignClinicToDoctorController
{
    public function __invoke(
        Doctor $doctor,
        AssignClinicToDoctorRequest $request,
        AssignClinicToDoctorAction $assignClinicToDoctorAction,
    ): JsonResponse {
        $doctor = $assignClinicToDoctorAction->execute($request->getClinicId());

        return DoctorResource::make($doctor)
            ->response();
    }
}
