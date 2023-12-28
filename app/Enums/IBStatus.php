<?php


namespace App\Enums;


interface IBStatus
{
    const UNPROCESSED = 'unprocessed';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

}
