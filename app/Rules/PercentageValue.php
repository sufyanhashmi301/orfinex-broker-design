<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PercentageValue implements Rule
{

    protected $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        // If the type is 'percentage', check the value constraints
        if ($this->type === 'percentage') {
            return $value >= 0 && $value <= 100; // Validates if the value is between 0 and 100
        }

        // If the type is not 'percentage', always return true
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be between 0 and 100 when type is percentage.';
    }
}
