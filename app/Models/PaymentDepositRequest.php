<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PaymentDepositRequest
 *
 * @property int $id
 * @property int $user_id
 * @property string $fields
 * @property string $status
 * @property string|null $bank_details
 * @property int|null $approved_by
 * @property Carbon|null $submitted_at
 * @property Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PaymentDepositRequest extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $table = 'payment_deposit_requests';

    protected $fillable = [
        'user_id',
        'fields',
        'status',
        'bank_details',
        'approved_by',
        'submitted_at',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'fields' => 'array',
        'bank_details' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the request
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get sanitized fields data
     */
    public function getSanitizedFieldsAttribute(): array
    {
        if (!is_array($this->fields)) {
            return [];
        }

        $sanitized = [];
        foreach ($this->fields as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = array_map('strip_tags', $value);
            } else {
                $sanitized[$key] = strip_tags($value);
            }
        }

        return $sanitized;
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent requests
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
