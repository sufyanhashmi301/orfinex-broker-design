<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSymbolGroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
           'name' => 'required|string|max:255',
            'symbols' => 'required|array',
            'symbols.*' => 'exists:symbols,id'
           
        ];
    }
}
