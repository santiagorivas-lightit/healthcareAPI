<?php

declare(strict_types=1);

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;
use Lightit\Users\App\Controllers\{
    GetUserController,
    DeleteUserController,
    ListUserController,
    StoreUserController,
    UpdateUserController
};
use Lightit\Doctors\App\Controllers\{
    GetDoctorController,
    DeleteDoctorController,
    ListDoctorController,
    StoreDoctorController,
    AssignClinicToDoctorController
};
use Lightit\Clinics\App\Controllers\{
    GetClinicController,
    DeleteClinicController,
    ListClinicController,
    StoreClinicController
 };
use Lightit\Appointments\App\Controllers\{
    ListAppointmentController,
    StoreAppointmentController,
    DeleteAppointmentController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')
    ->get('/me', fn(
        #[CurrentUser] $user
    ) => response()->json([
        'data' => $user,
    ]));

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')
    ->middleware([])
    ->group(static function (): void {
        Route::get('/', ListUserController::class);
        Route::post('/', StoreUserController::class);
        Route::prefix('/{user}')->group(static function (): void {
            Route::get('/', GetUserController::class)->withTrashed();
            Route::put('/',  UpdateUserController::class);
            Route::delete('/', DeleteUserController::class);
        })->whereNumber('user');
    });


/*
|--------------------------------------------------------------------------
| Doctors Routes
|--------------------------------------------------------------------------
*/
Route::prefix('doctors')
    ->group(static function (): void {
        Route::get('/', ListDoctorController::class);
        Route::post('/', StoreDoctorController::class);
        Route::prefix('/{doctor}')->group(static function (): void {
            Route::get('/', GetDoctorController::class)->withTrashed();
            Route::put('/', AssignClinicToDoctorController::class);
            Route::delete('/', DeleteDoctorController::class);
        })->whereNumber('doctor');
    });

/*
|--------------------------------------------------------------------------
| Clinics Routes
|--------------------------------------------------------------------------
*/
Route::prefix('clinics')
    ->group(static function (): void {
        Route::get('/', ListClinicController::class);
        Route::post('/', StoreClinicController::class);
        Route::prefix('/{clinic}')->group(static function (): void {
            Route::get('/', GetClinicController::class)->withTrashed();
            Route::delete('/', DeleteClinicController::class);
        })->whereNumber('clinic');
    });

/*
|--------------------------------------------------------------------------
| Appointments Routes
|--------------------------------------------------------------------------
*/
Route::prefix('appointments')
    ->group(static function (): void {
        Route::get('/', ListAppointmentController::class);
        Route::post('/', StoreAppointmentController::class);
        Route::delete('/{appointment}', DeleteAppointmentController::class)
            ->whereNumber('appointment');
    });
