<?php

namespace App\Rules;

use App\Enums\AccountTypePhase;
use Illuminate\Contracts\Validation\Rule;

class RequiredPhaseTypes implements Rule
{
    protected $requiredPhases = [
        // AccountTypePhase::EVALUATION,
        AccountTypePhase::FUNDED,
    ];

    protected $missingPhases = [];

    public function passes($attribute, $value)
    {
        // Reset missing phases for each validation run
        $this->missingPhases = array_diff($this->requiredPhases, array_column($value, 'type'));

        // Passes if there are no missing phases
        return empty($this->missingPhases);
    }

    public function message()
    {
        $messages = [];
        foreach ($this->missingPhases as $missingPhase) {
            $messages[] = match ($missingPhase) {
                // AccountTypePhase::EVALUATION => 'The Evaluation Phase is required.',
                AccountTypePhase::FUNDED => 'The Funded Phase is required.',
                default => 'Evaluation and Funded required phases are missing.',
            };
        }

        return implode(' ', $messages);
    }
}
