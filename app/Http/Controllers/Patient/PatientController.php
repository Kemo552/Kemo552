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
            return $this->returnException('Something went wrong!', $ex);
        }
    }

    // --- update a Selected Medical report, Using (id)
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
                return $this->returnException('Something went wrong!', $ex);
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
                return $this->returnException('Something went wrong!', $ex);
            }
        }
        return $this->returnNotFound('Medical report not found, or deleted');
    }
}
