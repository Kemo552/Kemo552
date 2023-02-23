<?php

use App\Http\Controllers\Patient\PatientController;
use Illuminate\Support\Facades\Route;

// ------ Doctor Reqeusts:
Route::group(['middleware' => ['assign-guard:patient']], function() {
    Route::post('test', [PatientController::class, 'test']);
    Route::post('login', [PatientController::class, 'login']);
    // -- Medical Report:
    Route::prefix('medical-report')->group(function () {
        Route::post('enter-data', [PatientController::class, 'setReportData']);
        Route::post('update-data/{id}', [PatientController::class, 'updateReportData']);
        Route::post('delete/{id}', [PatientController::class, 'deleteReport']);
    });
});
Route::post('register', [PatientController::class, 'register']);
