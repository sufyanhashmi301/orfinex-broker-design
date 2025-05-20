<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'discount_id' => 'required|integer',
            'condition' => 'required|string',
            'description' => 'required|string',
            'multiple_time_usage' => 'required|integer',
            'validity' => 'required|integer|min:1',
            'status' => 'required|integer',
        ];
    }


    // Optional: handle redirection with validation errors
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        notify()->error($validator->errors()->first());
        throw new \Illuminate\Validation\ValidationException($validator, redirect()->back()->withErrors($validator)->withInput());
    }
}
