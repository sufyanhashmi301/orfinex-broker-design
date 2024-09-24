<?php

namespace App\Models;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Traits\TransactionFilterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Transaction extends Model
{
    use HasFactory, Searchable,TransactionFilterable;

    protected $guarded = ['id'];

    protected $appends = ['day'];

    protected $casts = [
        'type' => TxnType::class,
        'status' => TxnStatus::class,
        'pay_amount' => 'double',
        'amount' => 'double',
    ];

    protected $searchable = [
        'amount',
        'tnx',
        'type',
        'method',
        'description',
        'status',
        'created_at',
    ];

    public function toSearchableArray(): array
    {
        return [
            'amount' => $this->amount,
            'tnx' => $this->tnx,
            'type' => $this->type,
            'method' => $this->method,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }

    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d, Y h:i');
    }

    public function getDayAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M');
    }

    public function scopeTnx($query, $tnx)
    {
        return $query->where('tnx', $tnx)->first();
    }

    public function referral()
    {
        return $this->referrals()->where('type', '=', $this->target_type);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referral_target_id', 'target_id')->where('type', '=', $this->target_type);
    }

    public function target()
    {
        return $this->belongsTo(ReferralTarget::class, 'target_id');
    }

    public function level()
    {
        return $this->belongsTo(LevelReferral::class, 'target_id', 'the_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function invest()
    {
        return $this->hasOne(Invest::class, 'transaction_id');
    }

    public function totalDeposit()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::ManualDeposit)
                ->orWhere('type', TxnType::Deposit);
        });
    }

    public function totalWithdraw()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->orWhere('type', TxnType::WithdrawAuto);
        });
    }

    public function totalInvestment()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Investment);
        });
    }

    public function totalProfit()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Interest)
                ->orWhere('type', TxnType::Bonus)
                ->orWhere('type', TxnType::SignupBonus);
        });
    }

    public function totalDepositBonus()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('target_id', '!=', null)
                ->where('target_type', 'deposit')
                ->where('type', TxnType::Referral);
        })->sum('amount');
    }

    public function totalInvestBonus()
    {
        return $this->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('target_id', '!=', null)
                ->where('target_type', 'investment')
                ->where('type', TxnType::Referral);
        })->sum('amount');
    }

    protected function method(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
        );
    }
    public function scopeApplyFilters(Builder $query, $filters)
    {

        if (!empty($filters['email'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('email', $filters['email']);
            });
        }


        if (isset($filters['type']) && $filters['type'] !== '') {
            $typeEnum = TxnType::tryFrom($filters['type']);  // Convert string to enum
            if ($typeEnum) {
                $query->where('type', $typeEnum->value);
            }
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $statusEnum = TxnStatus::tryFrom($filters['status']);  // Convert string to enum
            if ($statusEnum) {
                $query->where('status', $statusEnum->value);
            }
        }

        if (!empty($filters['created_at'])) {
            $dateRange = explode(' to ', $filters['created_at']);
            if (count($dateRange) === 2) {
                $startDate = Carbon::parse($dateRange[0])->startOfDay();  // Start of the day for the start date
                $endDate = Carbon::parse($dateRange[1])->endOfDay();      // End of the day for the end date
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        return $query;
    }
}
