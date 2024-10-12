<?php

namespace App\Http\Requests;

use App\Rules\PercentageValue;
use Illuminate\Foundation\Http\FormRequest;

class StoreBonusRequest extends FormRequest
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
            "bonus_name" => "string|required|max:255",
            "start_date" => "date|required",
            "last_date" => "date|required|after:start_date", // Make sure the last_date is a valid date after or equal to start_date",
            "type" => "string|required",
            "amount" => [
                "numeric",
                "required",
                new PercentageValue($this->input('type')), // Use the custom rule
            ],
            "process" => "string|required",
            "applicable_by" => "string|required",
            "kyc_verified" => "required",
            "first_deposit" => "required",
            "status" => "required",
            "terms_link" => "string",
            "description" => "string",
        ];
    }
}
