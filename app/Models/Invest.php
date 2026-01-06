<?php

namespace App\Models;

use App\Enums\InvestStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['created_time', 'is_cancel'];

    protected $casts = [
        'status' => InvestStatus::class,
    ];

    public function schema()
    {
        return $this->hasOne(Schema::class, 'id', 'schema_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    // Removed getCreatedAtAttribute to allow proper timezone conversion in controllers/views
    // Use toSiteTimezone() in DataTables and blade views for display
    
    public function getNextProfitTimeAttribute($value)
    {
        // Database stores in UTC, convert to site timezone for display
        return $value ? toSiteTimezone($value, 'M d, Y H:i') : null;
    }

    public function getCreatedTimeAttribute(): string
    {
        // Database stores in UTC, convert to site timezone for display
        return toSiteTimezone($this->attributes['created_at'], 'M d Y h:i');
    }

    public function getIsCancelAttribute(): string
    {

        if ($this->schema->schema_cancel) {
            // Use raw created_at attribute to avoid accessor recursion
            $createdAt = Carbon::parse($this->attributes['created_at'])->setTimezone('UTC');
            $expiryTime = $createdAt->copy()->addMinute($this->schema->expiry_minute);
            $now = Carbon::now('UTC');
            if ($expiryTime >= $now) {
                return true;
            }

            return false;
        }

        return false;
    }
}
