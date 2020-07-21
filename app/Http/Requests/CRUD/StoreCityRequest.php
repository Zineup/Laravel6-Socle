<?php

namespace App\Http\Requests\CRUD;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
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
            'name' =>  ['required', 'min:3', 'max:30'],
            'postal_code' => ['required', 'min:3', 'max:10'],
            'population' => ['required', 'integer', 'max:1000000000', 'min:500'],
            'region' => ['required', 'min:3', 'max:30'],
            'country' => ['required', 'min:3', 'max:30'],
        ];
    }
}
