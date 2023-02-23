<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;

// ------ Doctor Reqeusts:
Route::group(['middleware' => ['assign-guard:doctor']], function() {
    Route::post('test', [DoctorController::class, 'test']);
    Route::post('login', [DoctorController::class, 'login']);
});
Route::post('register', [DoctorController::class, 'register']);
