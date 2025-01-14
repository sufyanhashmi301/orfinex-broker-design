<?php

namespace App\Enums;

enum ContractStatusEnums: string
{
    const PENDING = 'contract_pending';
    const SIGNED = 'contract_signed';
    const EXPIRED = 'contract_expired';
}