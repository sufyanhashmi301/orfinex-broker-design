<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateSwapFreeAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $accountId = $this->route('swapBasedAccount');
        return [
            'forex_scheme_id' => 'required|exists:forex_schemas,id',
            'title' => 'required|string|max:255',
           'level_order' => [
                'required',
                'integer',
                Rule::unique('swap_free_accounts', 'level_order')->ignore($accountId),
            ],
            'group_tag' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ];
    }
}
