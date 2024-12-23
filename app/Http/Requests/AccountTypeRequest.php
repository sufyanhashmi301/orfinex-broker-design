<?php

namespace App\Http\Requests;

use App\Rules\MinDigits;
use App\Enums\AccountTypePhase;
use Illuminate\Validation\Rule;
use App\Enums\FundedSchemeTypes;
use App\Enums\PhaseApproval;
use App\Rules\RequiredPhaseTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountTypeRequest extends FormRequest
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
            'trader_type' => 'required',
            'title' => 'required',
            'leverage' => 'required',
            'is_scalable' => 'required',
            'is_weekend_holding' => 'required',
            'is_refundable' => 'required',
            'accounts_limit' => 'required|integer|min:1|max:50',
            'priority' => 'required|integer',
            'accounts_range_start' => array_merge(
                setting('is_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)],
                ['integer']
            ),
            'accounts_range_end' => array_merge(
                setting('is_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)],
                ['integer']
            ),
            'trading_days' => 'required|integer',
            'profit_share' => 'required|min:1|max:100|integer',
            'platform_group' => 'required',
            'type' => 'required',
            'phases' => ['required', 'array', new RequiredPhaseTypes()],
            'phases.*.type' => [
                'required',
                Rule::in([
                    AccountTypePhase::EVALUATION,
                    AccountTypePhase::VERIFICATION,
                    AccountTypePhase::FUNDED,
                ]) 
                
            ],
            'phases.*.phase_step' => 'required|numeric',
            'phases.*.phase_approval_method' => [
                'required',
                'string',
                Rule::in([
                    PhaseApproval::ADMIN,
                    PhaseApproval::AUTO,
                    PhaseApproval::PAYMENT,
                ])
            ],
            'phases.*.validity_period' => 'required|integer|min:1|max:12',
            'phases.*.server' => 'required|string',
            'phases.*.rules' => 'required|array|min:1', // Ensuring at least one rule is set
            'phases.*.rules.*.allotted_funds' => 'required|numeric',
            'phases.*.rules.*.daily_drawdown_limit' => 'required|numeric',
            'phases.*.rules.*.max_drawdown_limit' => 'required|numeric',
            'phases.*.rules.*.profit_target' => 'required|numeric',
            'phases.*.rules.*.price' => 'required|numeric',
            'phases.*.rules.*.discount' => 'required|numeric',
        ];
    }

    // Handle the validation
    protected function failedValidation(Validator $validator)
    {
        notify()->error($validator->errors()->first(), 'Error');
        
        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }
}
