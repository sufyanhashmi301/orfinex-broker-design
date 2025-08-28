<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'action',
        'model',
        'model_id',
        'admin_id',
        'changes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime'
    ];

    public $timestamps = false;

    /**
     * Get the admin who performed the action
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChangesAttribute(): string
    {
        if (empty($this->changes)) {
            return 'No changes recorded';
        }

        $formatted = [];
        foreach ($this->changes as $field => $change) {
            if (is_array($change) && isset($change['from'], $change['to'])) {
                $formatted[] = "{$field}: {$change['from']} → {$change['to']}";
            }
        }

        return implode(', ', $formatted);
    }

    /**
     * Scope for specific model
     */
    public function scopeForModel($query, string $model, int $modelId = null)
    {
        $query->where('model', $model);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Scope for specific admin
     */
    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
