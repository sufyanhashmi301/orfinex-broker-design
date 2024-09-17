<?php

namespace App\Enums;

enum KYCStatus: int
{
    // case None = 0; // Representing no KYC status
//    case Level1 = 1;
//    case Level2 = 2;
//    case Level3 = 3;
    case Pending = 4;
    case Rejected = 5;
}
