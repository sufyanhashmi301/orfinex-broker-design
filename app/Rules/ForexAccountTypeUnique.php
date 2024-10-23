<?php

namespace App\Rules;

use App\Models\Bonus;
use App\Models\ForexSchema;
use Illuminate\Contracts\Validation\Rule;

class ForexAccountTypeUnique implements Rule
{

    protected $bonusProcess;
    protected $lastDate;
    protected $status;
    protected $conflictingTypes = [];

    public function __construct($bonusProcess, $lastDate, $status)
    {
        $this->bonusProcess = $bonusProcess;
        $this->lastDate = $lastDate;
        $this->status = $status;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->lastDate < now() || $this->status == 0) {
            return true; // Skip validation
        }

        // Iterate over the selected forex account type IDs
        foreach ($value as $typeId) {
            $forexSchema = ForexSchema::find($typeId);

            // Check if any bonuses are associated with this forex schema
            foreach ($forexSchema->bonuses as $bonus) {
                // Check if the bonus is active (last_date is in the future and status is active)
                if ($bonus->process == $this->bonusProcess && $bonus->status == 1 && $bonus->last_date >= now()) {
                    // Store the conflicting account types and bonus info for error reporting
                    $this->conflictingTypes[] = [
                        'account_type' => $forexSchema->title,
                        'account_id' => $forexSchema->id,
                        'bonus_name' => $bonus->bonus_name,
                        'bonus_process' => $bonus->process,
                        'last_date' => \Carbon\Carbon::parse($bonus->last_date)->format('d F'),
                    ];
                }
            }
        }

        // If there are conflicting account types, return false
        return count($this->conflictingTypes) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // Prepare the error messages for conflicting account types
        $messages = [];
        foreach ($this->conflictingTypes as $conflict) {
            $messages[] = "• \"{$conflict['account_type']}\" account type has already been attached to \"{$conflict['bonus_name']}\" bonus (with {$conflict['bonus_process']} process) and is active until " . \Carbon\Carbon::parse($conflict['last_date'])->format('d F') . ". <br>";
        }

        // Return the error messages as bullet points, separated by a new line
        return implode("\n", $messages);
    }

}
