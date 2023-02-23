<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'report_date'        => 'required|date',
            'blood_groub'        => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alergies'           => 'string|nullable',
            'heart_disease'      => 'required|boolean',
            'blood_pressure'     => 'required|nullable:false',
            'previous_surgeries' => 'string|nullable',
            'doctor_name'        => 'required|nullable:false',
            'doctor_phone'       => 'required|nullable:false|numeric',
        ];
    }
}
