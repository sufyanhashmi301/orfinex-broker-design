<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class UserReferred
{
    use SerializesModels;

    public $referralId;

    public $user;
    public $multiLevelId;

    public function __construct($referralId, $user,$multiLevelId=null)
    {
        $this->referralId = $referralId;
        $this->user = $user;
        $this->multiLevelId = $multiLevelId;
    }

    public function broadcastOn()
    {
        return [];
    }
}
