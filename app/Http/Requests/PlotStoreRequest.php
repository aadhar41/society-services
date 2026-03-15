<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlotStoreRequest extends FormRequest
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
            'name' => 'required|string|max:80',
            'society' => 'required|integer',
            'block' => 'required|integer',
            'total_floors' => 'required|integer',
            'total_flats' => 'required|integer',
            'plot_area' => 'nullable|string|max:50',
            'description' => 'required|string|max:600',
        ];
    }

    public function attributes()
    {
        return [
            'total_floors' => 'Total Floors',
            'total_flats' => 'Total Flats',
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
