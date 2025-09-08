<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawAccount extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    protected $fillable = [
        "user_id",
        "withdraw_method_id",
        "method_name",
        "credentials",
        "status",
        "description"
    ];

    protected $casts = [
        "credentials" => "array"
    ];

    // Status constants
    const STATUS_PENDING = "pending";
    const STATUS_APPROVED = "approved";
    const STATUS_REJECTED = "rejected";

    public function method(): BelongsTo
    {
        return $this->belongsTo(WithdrawMethod::class, "withdraw_method_id")->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // Scope for approved accounts only
    public function scopeApproved($query)
    {
        return $query->where("status", self::STATUS_APPROVED);
    }
}
