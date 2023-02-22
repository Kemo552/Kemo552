<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorLoginRequest;
use App\Http\Requests\DoctorSignupRequest;
use App\Http\Traits\GeneralTraits;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    use GeneralTraits;
    public function test()
    {
        return response()->json(['state' => 'connected']);
    }

    // --- Registeration
    public function register(DoctorSignupRequest $regist)
    {
        $regist['password'] = bcrypt($regist['password']);
        try {
            $doctor = new Doctor($regist->all());
            $doctor->save();
            $token = $doctor->createToken('Register|' . $doctor->doctor_code)->plainTextToken;
            return $this->returnData('Successfully registered', $doctor, 'token', $token, 201);
        } catch (Exception $ex) {
            return $this->returnException('Something went wrong!', $ex);
        }
    }

    // --- Log-in
    public function login(DoctorLoginRequest $login)
    {
        $credentials = $login->only(['email', 'password']);
        if (Auth::guard('doctor')->attempt($credentials)) {
            try {
                /** @var App\Models\Doctor */
                $doctor = Auth::guard('doctor')->user();
                $token = $doctor->createToken('Login|' . $doctor->doctor_code)->plainTextToken;
                return $this->returnData('Successfully loged-in', $credentials['email'], 'token', $token);
            } catch (Exception $ex) {
                return $this->returnException('Someting went wrong!', $ex);
            }
        }
        return $this->returnUnauthorized('Unauthorized | Credentials not valid');
    }
}
