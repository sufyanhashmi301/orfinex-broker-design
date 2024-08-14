<?php

namespace App\Enums;

enum TxnStatus: string
{
    case None = 'none';
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';

    public function label(): string
    {
        return match($this) {
            self::Success => 'Success',
            self::Pending => 'Pending',
            self::Failed => 'Failed',
        };
    }
}
