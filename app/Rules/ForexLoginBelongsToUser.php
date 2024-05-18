<?php

namespace App\Rules;

use App\Models\ForexAccount;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ForexLoginBelongsToUser implements Rule
{
    protected $loginValue;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->loginValue = $value;
        $user = Auth::user();

        // Check if a forex account with the given login exists and belongs to the authenticated user
        return ForexAccount::where('login', $value)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The login value '{$this->loginValue}' does not belong to the currently authenticated user.";
    }
}
