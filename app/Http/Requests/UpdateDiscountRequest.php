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
            'applied_to' => 'nullable|string',
            'usage_limit' => 'required|integer|min:1',
//            'expire_at' => 'nullable|date',
            'status' => 'boolean',
        ];
    }

    /**
     * Handle failed validation.
     */
//    protected function failedValidation(Validator $validator) // Correctly using the Illuminate Validator
//    {
////        notify()->error(__('Please check the form for errors.'));
////
////        $response = redirect()->back()
////            ->withErrors($validator)
////            ->withInput();
////
////        throw new ValidationException($validator, $response);
//    }
}
