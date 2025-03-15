<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d, Y h:i');
    }

    public function owner()
    {
        return $this->belongsTo(Admin::class, 'lead_owner');
    }
}
