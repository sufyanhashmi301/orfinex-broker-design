<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSwapBasedAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
//        $accountId = $this->route('swap-multi-level');
//        dd($accountId);
        return [
            'forex_scheme_id' => 'required|exists:forex_schemas,id',
            'title' => 'required|string|max:255',
//            'level_order' => [
//                'required',
//                'integer',
//                Rule::unique('multi_levels', 'level_order')->ignore($accountId),
//            ],
            'group_tag' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'rebate_rules' => 'required|array',
            'rebate_rules.*' => 'exists:rebate_rules,id',
            'ib_group_id' => 'required|array', // Ensure this line is present
            'ib_group_id.*' => 'exists:ib_groups,id',
        ];
    }
}
