<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSwapBasedAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'account_type_id' => 'required|exists:forex_accounts,id',
            'title' => 'required|string|max:255',
            'level_order' => 'required|integer',
            'group_tag' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ];
    }
}
