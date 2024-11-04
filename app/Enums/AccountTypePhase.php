<?php

namespace App\Enums;

enum AccountTypePhase: string
{
    const EVALUATION = 'evaluation_phase';
    const VERIFICATION = 'verification_phase';
    const FUNDED = 'funded_phase';
}