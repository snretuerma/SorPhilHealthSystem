<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addCreditRecordRequest extends FormRequest
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
            'lname' => 'required',
            'fname' => 'required',
            'batch' => 'required',
            'admission' => 'required',
            'discharge' => 'required',
            'pf' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'patient_name.required' => 'Please input Name',
            'batch.required' => 'Please input Batch',
            'admission_date.required' => 'Please input Admission',
            'discharge_date.required' => 'Please input Discharge',
            'total.required' => 'Please input Professional fee',
        ];
    }
}
