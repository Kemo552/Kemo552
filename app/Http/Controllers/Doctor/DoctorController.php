<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\DoctorLoginRequest;
use App\Http\Requests\DoctorSignupRequest;
use App\Http\Traits\GeneralTraits;
use App\Models\Appointment;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            try {
                JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccess('Successfully logged out', 'no data');
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $ex) {
                return $this->returnNotFound('Invalid Token');
            }
        } else return $this->returnNotFound('Token not found!');
    }


    /**
     * Appointment Control Methods
     */

    // --- Set a New Appointment
    public function setAppointment(AppointmentRequest $valid)
    {
        $doctor_id = Auth::guard('doctor')->id();
        try {
            $appointment = new Appointment([
                'patient_id'  => $valid->patient_id,
                'doctor_id'   => $doctor_id,
                'status'      => $valid->status,
                'date'        => $valid->date,
                'description' => $valid->description,
                'notices'     => $valid->notices,
            ]);
            $appointment->save();
            return $this->returnSuccess('Successfully added an appoinment', $appointment);
        } catch (Exception $ex) {
            return $this->returnException('Something went wrong!', $ex->getMessage());
        }
    }

    // --- Change Date for a Selected Appointment, Using (id)
    public function changeAppointmentDate(Request $request, $id)
    {
        $update = Validator::validate($request->all(), ['date' => 'required']);
        $appointment = Appointment::find($id);
        if ($appointment) {
            try {
                $appointment->date = $update['date'];
                $appointment->save();
                return $this->returnSuccess('Successfully updated', $appointment);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Appointment not found');
    }

    // --- Change Status for a Selected Appointment, Using (id)
    public function changeAppointmentStatus(Request $request, $id)
    {
        $update = Validator::validate($request->all(), ['status' => 'required|in:in-progress,finished']);
        $appointment = Appointment::find($id);
        if ($appointment) {
            try {
                $appointment->status = $update['status'];
                $appointment->save();
                return $this->returnSuccess('Successfully updated', $appointment);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Appointment not found');
    }

    // --- Add Notice for a Selected Appointment, Using (id)
    public function AddNotice(Request $request, $id)
    {
        $update = Validator::validate($request->all(), ['notices' => 'required|string']);
        $appointment = Appointment::find($id);
        if ($appointment) {
            try {
                $appointment->notices = $update['notices'];
                $appointment->save();
                return $this->returnSuccess('Successfully updated', $appointment);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Appointment not found');
    }

    // --- Edit Details for a Selected Appointment, Using (id)
    public function editDetails(AppointmentRequest $valid, $id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            try {
                $appointment->patient_id  = $valid->patient_id;
                $appointment->status      = $valid->status;
                $appointment->date        = $valid->date;
                $appointment->description = $valid->description;
                $appointment->notices     = $valid->notices;
                $appointment->save();
                return $this->returnSuccess('Successfully updated', $appointment);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Appointment not found');
    }

    // --- Delete a Selected Appointment, Using (id)
    public function deleteAppointment($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            try {
                $app_id = $appointment->id;
                $appointment->delete();
                return $this->returnSuccess('Successfully deleted', 'appointment_id: '.$app_id);
            } catch (Exception $ex) {
                return $this->returnException('Something went wrong!', $ex->getMessage());
            }
        }
        return $this->returnNotFound('Appointment not found');
    }
}
