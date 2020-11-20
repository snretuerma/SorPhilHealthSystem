<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editHospitalRequest extends FormRequest
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
            'name' => 'required',
            'address' => 'required',
            'hospital_code' => 'required',
        ];
    }
    public function messages()
{
    return [
        'name.required' => 'Name is required',
        'address.required' => 'Address is required',
        'hospital_code.required' => 'Abbreviation required',
        
    ];
}
}


