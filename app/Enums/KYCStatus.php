<?php

namespace App\Enums;

enum KYCStatus: int
{
    case Verified = 1;
    case Pending = 2;
    case Failed = 3;
    case Advance_Verified = 4;
    case IB_Level= 5;
}
