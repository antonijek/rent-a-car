<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'producer' => 'required|string|max:255|min:3',
            'model' => 'required|string|max:255|min:3',
            'year' => 'required|integer|min:2010|max:' . date('Y'),
            'price' => 'required|numeric|between:20,200',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

        ];
    }
}
