<?php
// Step 1: Create the IBDistributionEvent
// app/Events/IBDistributionEvent.php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IBDistributionEvent
{
    use Dispatchable, SerializesModels;

    public $amount;
    public $user_id;
    public $login;

    public function __construct($amount, $user_id, $login)
    {
        $this->amount = $amount;
        $this->user_id = $user_id;
        $this->login = $login;
    }
}
