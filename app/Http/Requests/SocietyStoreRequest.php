<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocietyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // dd($this->route('society'));
        // dd($this->method());
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
            'name' => 'required|string|max:80',
            'registration_no' => 'nullable|string|max:50',
            'contact' => 'required|integer|digits:10',
            'contact_email' => 'nullable|email|max:100',
            'office_contact' => 'nullable|string|max:50',
            'postcode' => 'required|integer|digits:6',
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'address' => 'required|string|max:250',
            'description' => 'required|string|max:500',
            'map_link' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            "*.required" => "The :attribute field cannot be empty.",
        ];
    }
}
