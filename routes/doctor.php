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
});
Route::post('login', [DoctorController::class, 'login']);
Route::post('register', [DoctorController::class, 'register']);
