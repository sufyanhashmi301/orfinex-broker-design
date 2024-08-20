<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRebateRuleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'rule_type_id' => 'required',
            'rebate_amount' => 'required',
            'per_lot' => 'required|integer',
            'status' => 'required',
            'symbol_groups' => 'required|array',
            'symbol_groups.*' => 'exists:symbol_groups,id'
           
        ];
    }
}
