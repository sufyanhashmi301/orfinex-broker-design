<?php

namespace App\Enums;

enum TxnStatus: string
{
    case None = 'none';
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';
    case Review = 'review';
    case Expired = 'expired';

    

    public function label(): string
    {
        return match($this) {
            self::None => 'None',
            self::Success => 'Success',
            self::Pending => 'Pending',
            self::Failed => 'Failed',
            self::Review => 'Review',
            self::Expired => 'Expired',
        };
    }
}
