<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Change this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100|required_if:type,percentage',
            'fixed_amount' => 'nullable|numeric|min:0|required_if:type,fixed',
            'applied_to' => 'required|array',
            'usage_limit' => 'required|integer|min:1',
            'expire_at' => 'nullable|date',
            'status' => 'boolean',
        ];
    }

    /**
     * Custom error messages for validation failures.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'The discount code is required.',
            'code.unique' => 'This discount code already exists.',
            'type.required' => 'The discount type is required.',
            'percentage.required_if' => 'The percentage value is required for percentage-based discounts.',
            'fixed_amount.required_if' => 'The fixed amount value is required for fixed discounts.',
            'usage_limit.required' => 'The usage limit is required.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        notify()->error($validator->errors()->first(), __('Error'));

        throw new ValidationException($validator, redirect()->back()->withErrors($validator)->withInput());
    }
}
