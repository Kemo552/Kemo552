<?php

use App\Http\Controllers\Patient\PatientController;
use Illuminate\Support\Facades\Route;

// ------ Doctor Reqeusts:
Route::group(['middleware' => ['assign-guard:doctor']], function() {
    Route::post('test', [PatientController::class, 'test']);
    Route::post('login', [PatientController::class, 'login']);
});
Route::post('register', [PatientController::class, 'register']);
