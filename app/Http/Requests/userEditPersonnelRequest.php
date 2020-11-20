<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userEditPersonnelRequest extends FormRequest
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
            'last_name' => 'required|max:50',
            'first_name' => 'required|max:50',
            'middle_name' => 'required|max:50',
            'sex' => 'required',
            'is_private' => 'required',
            'designation' => 'required',
            'birthdate' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'last_name.required' => 'Lastname is required',
            'first_name.required' => 'Firstname is required',
            'middle_name.required' => 'Middlename is required',
            'sex.required' => 'Sex is required',
            'is_private.required' => 'Please choose staff type',
            'designation.required' => 'Please choose staff designation',
            'birthdate.required' => 'Birthdate is required',
            'last.max' => 'Lastname should not be greater than 50 characters',
            'first.max' => 'Firstname should not be greater than 50 characters',
            'middle.max' => 'Middlename should not be greater than 50 characters',

        ];
    }
}
