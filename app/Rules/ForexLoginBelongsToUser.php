<?php

namespace App\Rules;

use App\Models\ForexAccount;
use App\Models\User;
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->loginValue = $value;
        $user = Auth::user();
        $status = false;
        // Check if a account with the given login exists and belongs to the authenticated user
        $forexAccount = ForexAccount::where('login', $value)
            ->where('user_id', $user->id)
            ->where('account_type', 'real')
            ->exists();
        $ibAndMIB = User::where('id', $user->id)
                     ->where(function ($query) use ($value) {
                        $query->where('ib_login', $value)
                            ->orWhere('multi_ib_login', $value);
                        })->exists();
        if ($forexAccount || $ibAndMIB) {
            $status = true;
        }

        return $status;
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
