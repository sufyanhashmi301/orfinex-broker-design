<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'salutation',
        'first_name',
        'last_name',
        'client_email',
        'phone',
        'source_id',
        'stage_id',
        'lead_owner',
        'company_name',
        'website',
        'office_phone_number',
        'country',
        'state',
        'city',
        'postal_code',
        'address'
    ];

    public function stages()
    {
        return $this->belongsTo(LeadStage::class,  'id');
    }
}
