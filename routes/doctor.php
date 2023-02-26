<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use Illuminate\Support\Facades\Auth;

// ------ Doctor Reqeusts:
Route::group(['middleware' => ['assign-guard:doctor']], function () {
    // Contians any authenticate-based methods
    Route::post('test', [DoctorController::class, 'test']);
    Route::post('profile', function () {
        return Auth::user();
    });
    Route::post('logout', [DoctorController::class, 'logout']);

    // -- Appointment:
    Route::group(['prefix' => 'appointment', 'middleware' => 'assign-guard:doctor'], function () {
        Route::post('enter-data', [DoctorController::class, 'setAppointment']);
        Route::post('change-date/{id}', [DoctorController::class, 'changeAppointmentDate']);
        Route::post('change-status/{id}', [DoctorController::class, 'changeAppointmentStatus']);
        Route::post('add-notice/{id}', [DoctorController::class, 'AddNotice']);
        Route::post('edit-details/{id}', [DoctorController::class, 'editDetails']);
        Route::post('delete/{id}', [DoctorController::class, 'deleteAppointment']);
    });
});
Route::post('login', [DoctorController::class, 'login']);
Route::post('register', [DoctorController::class, 'register']);
