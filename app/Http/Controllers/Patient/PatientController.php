<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalReportRequest;
use App\Http\Requests\PatientLoginRequest;
use App\Http\Requests\PatientSigupRequest;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTraits;
use App\Models\MedicalReport;
use App\Models\Patient;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientController extends Controller
{
    use GeneralTraits;
    public function test()
    {
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
            return $this->returnException('Something went wrong!', $ex->getMessage());
        }
    }

    // --- Logging-in
    public function login(PatientLoginRequest $login)
    {
        $credentials = $login->only(['email', 'password']);
        if (Auth::guard('patient')->attempt($credentials)) {
            try {
                /** @var App\Models\Patient */
                $patient = Auth::guard('patient')->user();
                $token = Auth::guard('patient')->attempt($credentials); //$patient->createToken('Login|' . $patient->email)->plainTextToken;
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

    /**
     * Medical Report Control Methods
     */

    // --- Set a New Medical report
    public function setReportData(MedicalReportRequest $data)
    {
        try {
            $report = new MedicalReport($data->all());
            $report->save();
            return $this->returnSuccess('Successfully entered', $report);
        } catch (Exception $ex) {
            return $this->returnException('Something went wrong!', $ex->getMessage());
        }
    }

    // --- Update a Selected Medical report, Using (id)
    public function updateReportData(MedicalReportRequest $update, $id)
    {
        $report = MedicalReport::find($id);
        if ($report) {
            try {
                $report->report_date         = $update->report_date;
                $report->blood_groub         = $update->blood_groub;
                $report->alergies            = $update->alergies;
                $report->heart_disease       = $update->heart_disease;
                $report->blood_pressure      = $update->blood_pressure;
                $report->previous_surgeries  = $update->previous_surgeries;
                $report->doctor_name         = $update->doctor_name;
                $report->doctor_phone        = $update->doctor_phone;
                $report->save();

                return $this->returnSuccess('Successfully updated', $report);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Medical report not found');
    }

    // --- Delete a Selected Medical report, Using (id)
    public function deleteReport($id)
    {
        $report = MedicalReport::find($id);
        if ($report) {
            try {
                $report->delete();
                return $this->returnSuccess('Successfully deleted the selected medical report', 'no data');
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Medical report not found, or deleted');
    }

    // --- Get All Medical Reports that're related to an Authenticated User
    // public function getAllReports()
    // {
    //     try {
    //         $user_id = Auth::guard('patient')->id();
    //         $reports = MedicalReport::get(); //DB::table('medical_reports')->where('user_id', $user_id);
    //         return $this->returnSuccess('Successfully found it', $reports);
    //     } catch (Exception $ex) {
    //         return $this->returnException('Something went wrong!', $ex->getMessage());
    //     } catch (JWTException $ex) {
    //         return $this->returnException('Invalid Token', $ex->getMessage());
    //     }
    // }
}
