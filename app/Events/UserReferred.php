<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class UserReferred
{
    use SerializesModels;

    public $referralId;

    public $user;
    public $schemaID;

    public function __construct($referralId, $user,$schemaID=null)
    {
        $this->referralId = $referralId;
        $this->user = $user;
        $this->schemaID = $schemaID;
    }

    public function broadcastOn()
    {
        return [];
    }
}
