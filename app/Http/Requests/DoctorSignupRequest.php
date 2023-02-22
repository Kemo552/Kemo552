<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorSignupRequest extends FormRequest
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
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'birth_date'    => 'required|date',
            'doctor_code'   => 'required',
            'profession'    => 'nullable|string',
            'about_me'      => 'nullable|string',
            'email'         => 'required|email',
            'phone'         => 'required|numeric',
            'profile_image' => 'nullable|string',
            'password'      => 'required|string',
        ];
    }
}
