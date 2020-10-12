<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminEditBudgetRequest extends FormRequest
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
            'total' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'hospital_code' => 'required',

        ];
    }
    public function messages()
{
    return [
        'total.required' => 'Amount is required',
        'start_date.required' => 'Start date is required',
        'end_date.required' => 'End date is required',
        'hospital_code.required' => 'Hospital is required',
        
    ];
}
}


