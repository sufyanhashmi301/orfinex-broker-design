<?php


namespace App\Enums;


interface ForexTradingStatus
{
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const LOCKED = 'locked';
    const SUSPEND = 'suspend';
    const DELETED = 'deleted';
    const ARCHIVE = 'archive';
}
