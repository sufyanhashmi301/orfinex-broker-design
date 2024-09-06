<?php

namespace App\Enums;

enum KYCStatus: int
{
    case None = 0; // Representing no KYC status
    case Basic_Verified = 1;
    case Advance_Verified = 4;
    case IB_Level= 5;
    case Pending = 2;
    case Failed = 3;

}
