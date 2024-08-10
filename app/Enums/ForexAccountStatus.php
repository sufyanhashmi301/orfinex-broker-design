<?php

namespace App\Enums;

interface ForexAccountStatus
{
    const Ongoing = 'ongoing';
    const Completed = 'completed';
    const Pending = 'pending';
    const Canceled = 'canceled';
    const Archive = 'archive';
}
