<?php

namespace App\Enums;

use Twilio\TwiML\Voice\Reject;

enum KYCStatus: int
{
    // case None = 0; // Representing no KYC status
    case Level1 = 1; //approve level 1
    case Pending = 2; //manual pending for level2
    case Rejected = 3;//manual rejected for level3
    case Level2 = 4; //approve level 2
    case PendingLevel3 = 5; //manual pending for level3
    case RejectLevel3 = 6; //manual rejected for level3
    case Level3 = 7; //approve level 3
}
