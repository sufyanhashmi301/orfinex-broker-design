<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Removed getCreatedAtAttribute to allow proper timezone conversion in controllers/views
    // Use toSiteTimezone() in DataTables and blade views for display
}
