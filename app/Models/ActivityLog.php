<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id', 'actor_type', 'action', 'description', 'meta', 'ip', 'location', 'agent',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i');
    }
}
