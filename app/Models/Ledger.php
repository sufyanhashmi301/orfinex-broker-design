<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    public $timestamps = ["created_at"];
    const UPDATED_AT = null;
    /**
     * @var string[]
     */
    protected $fillable = [
        'transaction_id',
        'account_id',
        'debit',
        'credit',
        'balance',
    ];
}
