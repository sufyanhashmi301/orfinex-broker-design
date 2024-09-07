<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinDigits implements Rule
{
    protected $minDigits;

    public function __construct($minDigits)
    {
        $this->minDigits = $minDigits;
    }

    public function passes($attribute, $value)
    {
        return preg_match('/^\d{' . $this->minDigits . ',}$/', $value);
    }

    public function message()
    {
        return 'The :attribute must be at least ' . $this->minDigits . ' digits.';
    }
}
