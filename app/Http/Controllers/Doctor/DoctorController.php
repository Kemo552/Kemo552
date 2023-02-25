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
use Tymon\JWTAuth\Facades\JWTAuth;

class DoctorController extends Controller
{
    use GeneralTraits;
    public function test()
    {
        return $this->returnSuccess('connected', 'no data');
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
            return $this->returnException('Something went wrong!', $ex->getMessage());
        }
    }

    // --- Logging-in
    public function login(DoctorLoginRequest $login)
    {
        $credentials = $login->only(['email', 'password']);
        if (Auth::guard('doctor')->attempt($credentials)) {
            try {
                /** @var App\Models\Doctor */
                $doctor = Auth::guard('doctor')->user();
                $token = Auth::guard('doctor')->attempt($credentials); //$doctor->createToken('Login|' . $doctor->doctor_code)->plainTextToken;
                return $this->returnData('Successfully loged-in', $credentials['email'], 'token', $token);
            } catch (Exception $ex) {
                return $this->returnException('Someting went wrong!', $ex->getMessage());
            }
        }
        return $this->returnUnauthorized('Unauthorized | Credentials not valid');
    }

    // --- Logging-out
    public function logout(Request $logout)
    {
        $token = $logout->header('auth-token');
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
            return $this->returnSuccess('Successfully logged out', 'no data');
        } else return $this->returnNotFound('Token not found!');
    }
}
