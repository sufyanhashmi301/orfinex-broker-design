<?php

namespace App\Http\Requests;

use App\Models\Bonus;
use App\Rules\PercentageValue;
use App\Rules\BonusRemovalAmountRule;
use App\Rules\ForexAccountTypeUnique;
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
        // when updating detach all the forex_schemas of current bonus
        if ($this->isMethod('put')) {
            $bonusId = (int) $this->input('bonus_id'); // Get the bonus ID from the route

            $currentBonus = Bonus::find($bonusId);
            if ($currentBonus) {
                $currentBonus->forex_schemas()->detach();
            }
        }
        
        
        return [
            "forex_account_types" => [
                new ForexAccountTypeUnique($this->input('process'), $this->input('last_date'), $this->input('status')),
            ],
            "bonus_name" => "string|required|max:255",
            "start_date" => "date|required",
            "last_date" => "date|required|after:start_date", // Make sure the last_date is a valid date after or equal to start_date",
            "type" => "string|required",
            "bonus_removal_type" => "string|required",
            "bonus_removal_amount" => 'numeric|required',
            "amount" => [
                "numeric",
                "required",
                new PercentageValue($this->input('type')), // Use the custom rule
            ],
            "process" => "string|required",
            "applicable_by" => "string|required",
            "kyc_slug" => "string|required",
            "first_or_every_deposit" => "string|required",
            "status" => "required",
            "terms_link" => "string",
            "description" => "string",
        ];
    }

    public function messages()
    {
        return [
            'forex_account_types.required' => 'Select atleast one forex account type.',
        ];
    }
}
