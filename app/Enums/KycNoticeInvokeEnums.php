<?php

namespace App\Enums;

enum KycNoticeInvokeEnums: string
{
    const ACCOUNT_PURCHASE = 'account_purchase';
    const VERIFICATION_PHASE = 'verification_phase';
    const FUNDED_PHASE = 'funded_phase';
    const PAYOUT = 'payout';
}