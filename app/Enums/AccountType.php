<?php

namespace App\Enums;

enum AccountType: string
{
    const CHALLENGE = 'challenge';
    const FUNDED = 'funded';
    const AUTO_EXPIRE = 'auto_expire';
}