<?php

namespace App\Http\Requests;

use App\Rules\WalletBelongsToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserWithdrawRequest extends FormRequest
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
            'wallet_id' => ['required', 'integer', new WalletBelongsToUser],
            'withdraw_account' => ['required'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ];
    }

    public function messages()
    {
        return [
            'wallet_id.required' => __('Select withdrawal details.'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        notify()->error($validator->errors()->first(), 'Error');

        throw new ValidationException($validator, redirect()->back());
    }
}
