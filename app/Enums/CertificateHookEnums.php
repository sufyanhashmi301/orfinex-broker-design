<?php

namespace App\Enums;

enum CertificateHookEnums: string
{
    const PHASE_ONE = 'on_phase_one_success';
    const PHASE_TWO = 'on_phase_two_success';
    const AFFILIATE_PAYOUT = 'on_affiliate_payout_success';
    const MAX_ALLOCATION = 'on_max_allocation_success';
}