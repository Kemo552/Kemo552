<?php

use App\Http\Controllers\Patient\PatientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ------ Doctor Reqeusts:
Route::group(['middleware' => ['assign-guard:patient']], function () {
    // Contians any authenticate-based methods
    Route::post('test', [PatientController::class, 'test']);
    Route::post('profile', function () {
        return Auth::user();
    });
    Route::post('logout', [PatientController::class, 'logout']);

    // -- Medical Report:
    Route::group(['prefix' => 'medical-report', 'middleware' => 'assign-guard:patient'], function () {
        Route::post('enter-data', [PatientController::class, 'setReportData']);
        Route::post('update-data/{id}', [PatientController::class, 'updateReportData']);
        Route::post('delete/{id}', [PatientController::class, 'deleteReport']);
        // Route::post('reports', [PatientController::class, 'getAllReports']);
    });
});
Route::post('register', [PatientController::class, 'register']);
Route::post('login', [PatientController::class, 'login']);
