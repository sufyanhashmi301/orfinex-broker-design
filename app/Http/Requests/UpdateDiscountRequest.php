<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; // Correct Validator import
use Illuminate\Validation\ValidationException;

class UpdateDiscountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $discountId = $this->route('discount')->id;

        return [
            'code_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:discounts,code,' . $discountId,
            'type' => 'required|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100|required_if:type,percentage',
            'fixed_amount' => 'nullable|numeric|min:0|required_if:type,fixed',
            'applied_to' => 'required|array',
            'usage_limit' => 'required|integer|min:1',
            'expire_at' => 'required|date',
            'status' => 'boolean',
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
