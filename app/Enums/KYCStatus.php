<?php

namespace App\Enums;

enum KYCStatus: int
{
    // case None = 0; // Representing no KYC status
    case Pending = 4;
    case Rejected = 5;
}
