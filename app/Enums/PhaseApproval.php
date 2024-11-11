<?php


namespace App\Enums;


interface PhaseApproval
{
    const PAYMENT = 'on_payment_approval';
    const ADMIN = 'admin_approval';
    const AUTO = 'auto_approval';
}
