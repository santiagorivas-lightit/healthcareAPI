<?php

declare(strict_types=1);

namespace Lightit\Doctors\App\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Doctors\App\Requests\AssignClinicToDoctorRequest;
use Lightit\Doctors\Domain\Actions\AssignClinicToDoctorAction;
use Lightit\Doctors\Domain\Models\Doctor;

#[Group('Doctors')]
final readonly class AssignClinicToDoctorController
{
    public function __invoke(
        Doctor $doctor,
        AssignClinicToDoctorRequest $request,
        AssignClinicToDoctorAction $assignClinicToDoctorAction,
    ): JsonResponse {
        $assignClinicToDoctorAction->execute($doctor, $request->getClinicId());

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
