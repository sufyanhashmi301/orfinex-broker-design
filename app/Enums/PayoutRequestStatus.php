<?php

namespace App\Enums;

enum PayoutRequestStatus: string
{
    const APPROVED = 'approved';
    const DECLINE = 'decline';
    const PENDING = 'pending';
}