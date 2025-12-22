<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForexAccountStatementLog extends Model
{
    use HasFactory;

    protected $table = 'forex_account_statement_logs';

    protected $fillable = [
        'forex_account_id',
        'account_login',
        'user_email',
        'statement_date',
        'status',
        'error_message',
        'pdf_size',
        'sent_at',
    ];

    protected $casts = [
        'statement_date' => 'date',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the forex account that owns the log.
     */
    public function forexAccount(): BelongsTo
    {
        return $this->belongsTo(ForexAccount::class);
    }

    /**
     * Scope for successful logs.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed logs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for logs within date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('statement_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->pdf_size) {
            return 'N/A';
        }

        $bytes = $this->pdf_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'sent' ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get status display text.
     */
    public function getStatusTextAttribute(): string
    {
        return $this->status === 'sent' ? 'Sent' : 'Failed';
    }
}