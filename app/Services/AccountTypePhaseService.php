<?php

namespace App\Services;

use App\Enums\InterestPeriod;
use App\Models\AccountType;
use App\Models\AccountTypePhase;
use App\Models\AccountTypePhaseRule;

class AccountTypePhaseService
{

     /**
     * Generating unique Id for rules
     */
    private function generateUniqueId()
    {
        do {
            $unique_id = mt_rand(10000, 99999);
        } while (AccountTypePhaseRule::where('unique_id', 'R-' . $unique_id)->exists());
    
        return $unique_id;
    }

    /**
     * Generate unique IDs for the rules in the first phase
     */
    private function generateUniqueIdsForRules($rules)
    {
        
        $uniqueIds = [];
        foreach ($rules as $index => $rule) {

            // if unique id is  provided
            if(isset($rule['unique_id']) && $rule['unique_id'] != ''){
                $uniqueIds[] = $rule['unique_id'];
            }else{
                $uniqueIds[] = 'R-' . $this->generateUniqueId();
            }
        }
        return $uniqueIds;
    }

    /**
     * Create Phases
     */
    public function createPhases($account_type_id, $phases)
    {
        $uniqueIds = []; // Store unique IDs for rules across phases

        foreach ($phases as $phaseIndex => $phase) {
            $phases_data = [
                'account_type_id' => $account_type_id,
                'type' => $phase['type'],
                'phase_approval_method' => $phase['phase_approval_method'],
                'phase_step' => $phase['phase_step'],
                'validity_period' => $phase['validity_period'],
                'term_type' => InterestPeriod::MONTHLY,
                'server' => $phase['server'],
            ];

            $new_phase = AccountTypePhase::create($phases_data);

            // If this is the first phase, generate unique IDs for its rules
            if ($phaseIndex === 0) {
                $uniqueIds = $this->generateUniqueIdsForRules($phase['rules']);
            }

            // Creating Rules for each Phase, reusing unique IDs
            $this->createRules($new_phase->id, $phase['rules'], $uniqueIds);
        }
    }

    /**
     * Create Rules for phase
     */
    private function createRules($phase_id, $rules, $uniqueIds)
    {
        foreach ($rules as $index => $rule) {
            $unique_id = $uniqueIds[$index] ?? ('R-' . $this->generateUniqueId());

            AccountTypePhaseRule::create([
                'account_type_phase_id' => $phase_id,
                'unique_id' => $unique_id,
                'allotted_funds' => $rule['allotted_funds'],
                'daily_drawdown_limit' => $rule['daily_drawdown_limit'],
                'max_drawdown_limit' => $rule['max_drawdown_limit'],
                'profit_target' => $rule['profit_target'],
                'trading_days' => $rule['trading_days'],
                'amount' => $rule['price'],
                'total' => $rule['price'],
                'fee' => 0,
                'discount' => $rule['discount'],
                'is_new_order' => $rule['is_new_order'] ?? 0,
            ]);
        }
    }

   /**
     * Update Phases
     */
    public function updatePhases($account_type_id, $phases)
    {
        $account_type = AccountType::findOrFail($account_type_id);

        // Prepare the submitted phase IDs
        $submittedPhaseIds = collect($phases)->pluck('id')->toArray();

        // Get existing phase IDs
        $existingPhaseIds = $account_type->accountTypePhases->pluck('id')->toArray();

        // Determine phases to delete
        $phasesToDelete = array_diff($existingPhaseIds, $submittedPhaseIds);
        AccountTypePhase::destroy($phasesToDelete);

        // Loop through submitted phases to update or create them
        foreach ($phases as $phaseIndex => $phase) {
            // Update or create the phase
            $phaseInstance = $account_type->accountTypePhases()->updateOrCreate(
                ['id' => $phase['id'] ?? null],
                [
                    'type' => $phase['type'],
                    'phase_approval_method' => $phase['phase_approval_method'],
                    'phase_step' => $phase['phase_step'],
                    'validity_period' => $phase['validity_period'],
                    'term_type' => InterestPeriod::MONTHLY,
                    'server' => $phase['server'],
                ]
            );

            if ($phaseIndex === 0) {
                $uniqueIds = $this->generateUniqueIdsForRules($phase['rules']);
            }

            // Update or create rules for the current phase
            $this->updateRules($phaseInstance->id, $phase['rules'], $uniqueIds);
        }
    }

    /**
     * Update Rules for a phase, reusing unique IDs for existing rules and generating new IDs for new rules
     */
    private function updateRules($phase_id, $rules, $uniqueIds)
    {
        $phase = AccountTypePhase::findOrFail($phase_id);

        // Get current rules and map their unique IDs by rule ID
        $existingRules = $phase->accountTypePhaseRules->pluck('unique_id', 'id')->toArray();

        // Loop through each rule and update or create it with the correct unique ID
        foreach ($rules as $index => $ruleData) {
            // Check if the rule already exists; reuse the unique ID or generate a new one if it's a new rule
            $unique_id = $uniqueIds[$index] ?? ('R-' . $this->generateUniqueId());

            // Update or create the rule with the determined unique ID
            $phase->accountTypePhaseRules()->updateOrCreate(
                ['id' => $ruleData['id'] ?? null],
                [
                    'unique_id' => $unique_id,
                    'allotted_funds' => $ruleData['allotted_funds'],
                    'daily_drawdown_limit' => $ruleData['daily_drawdown_limit'],
                    'max_drawdown_limit' => $ruleData['max_drawdown_limit'],
                    'profit_target' => $ruleData['profit_target'],
                    'trading_days' => $ruleData['trading_days'],
                    'amount' => $ruleData['price'],
                    'total' => $ruleData['price'],
                    'fee' => 0,
                    'discount' => $ruleData['discount'],
                    'is_new_order' => isset($ruleData['is_new_order']) && $ruleData['is_new_order'] === 'on' ? 1 : 0,
                ]
            );
        }

        // Delete any rules in the database that weren’t in the submitted data
        $rules_wrt_uniqueid = AccountTypePhaseRule::where('unique_id', 'R-14003')->get();

        foreach($rules_wrt_uniqueid as $rule) {
            if(!$rule->accountTypePhase) {
                $rule->delete();
            } 
        }

    }




}
