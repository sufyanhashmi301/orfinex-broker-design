<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserAssignBonusRequest extends FormRequest
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
            'target_id' => 'required',
            'amount' => 'required',
            'comment' => 'string',
            'bonus_type' => 'required|string',
            'target_type' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The amount field is required.',
            'target_id.required' => 'The account field is required.',
            'target_type.required' => 'The type of the account is missing.',
        ];
    }

    // Override the failedValidation method
    protected function failedValidation(Validator $validator) {
        // Here you can execute any code you need when validation fails
        // For example, you can log the error or send a notification
        $errors = $validator->errors();
        $firstError = $errors->first();

        // Example: Send an error notification
        notify()->error($firstError, 'Validation Error');

        // Throw a ValidationException, which Laravel handles automatically
        throw new ValidationException($validator);
    }
}
