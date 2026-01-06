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

    // Removed getCreatedAtAttribute to allow proper timezone conversion in controllers/views
    // Use toSiteTimezone() in DataTables and blade views for display

    public function owner()
    {
        return $this->belongsTo(Admin::class, 'lead_owner');
    }
}
