<?php

namespace App\Enums;

enum TxnStatus: string
{
    case None = 'none';
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';
}
