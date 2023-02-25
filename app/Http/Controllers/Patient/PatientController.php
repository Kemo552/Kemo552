<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientLoginRequest;
use App\Http\Requests\PatientSigupRequest;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTraits;
use App\Models\Patient;
use Exception;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    use GeneralTraits;
    public function test(){
        return $this->returnSuccess('connected', 'no data');
    }

    // --- Registeration
    public function register(PatientSigupRequest $regist)
    {
        $regist['password'] = bcrypt($regist['password']);
        try {
            $patient = new Patient($regist->all());
            $patient->save();
            $token = $patient->createToken('Register|' . $patient->email)->plainTextToken;
            return $this->returnData('Successfully registered', $patient, 'token', $token, 201);
        } catch (Exception $ex) {
            return $this->returnException('Something went wrong!', $ex);
        }
    }

    // --- Log-in
    public function login(PatientLoginRequest $login)
    {
        $credentials = $login->only(['email', 'password']);
        if (Auth::guard('patient')->attempt($credentials)) {
            try {
                /** @var App\Models\Patient */
                $patient = Auth::guard('patient')->user();
                $token = $patient->createToken('Login|' . $patient->email)->plainTextToken;
                return $this->returnData('Successfully loged-in', $credentials['email'], 'token', $token);
            } catch (Exception $ex) {
                return $this->returnException('Someting went wrong!', $ex);
            }
        }
        return $this->returnUnauthorized('Unauthorized | Credentials not valid');
    }
}
