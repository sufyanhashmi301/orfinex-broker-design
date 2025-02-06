<?php

namespace App\Models;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Traits\UserFilterable;
use Carbon\Carbon;
use Coderflex\LaravelTicket\Concerns\HasTickets;
use Coderflex\LaravelTicket\Contracts\CanUseTickets;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanUseTickets, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasTickets,UserFilterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ranking_id',
        'rankings',
        'avatar',
        'first_name',
        'last_name',
        'nickname',
        'country',
        'phone',
        'username',
        'email',
        'email_verified_at',
        'gender',
        'date_of_birth',
        'city',
        'zip_code',
        'address',
        'comment',
        'status',
        'google2fa_secret',
        'two_fa',
        'deposit_status',
        'withdraw_status',
        'password',
        'kyc_token',
        'kyc_created_at',
        'notes',
    ];

    protected $appends = [
        'full_name',
    ];

    // ---- Optimization ----
    // User who referred this user
    public function referrer() {
        return $this->belongsTo(User::class, 'referred_by');
    }

    // Users referred by this user
    public function referrals() {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function userAffiliate() {
        return $this->hasOne(UserAffiliate::class);
    }

    public function accountTypeInvestment() {
        return $this->hasMany(AccountTypeInvestment::class);
    }

    public function accountTrial() {
        return $this->hasOne(AccountTrial::class);
    }

    public function kyc() {
        return $this->hasOne(Kyc::class);
    }
    // ---- Optimization ----

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_fa' => 'boolean',
    ];

    public function getUpdatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['updated_at'])->format('M d Y h:i');
    }

    public function getFullNameAttribute(): string
    {
        $firstName = $this->attributes['first_name'] ?? 'fname';
        $lastName = $this->attributes['last_name'] ?? 'lname';

        return ucwords("{$firstName} {$lastName}");
    }
    
    public function user_metas()
    {
        return $this->hasMany(UserMeta::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function totalDeposit($days = null)
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Deposit)
                ->orWhere('type', TxnType::ManualDeposit);
        });
        if (null != $days) {
            $sum->where('created_at', '>=', Carbon::now()->subDays((int) $days));
        }
        $sum = $sum->sum('amount');
        return round($sum, 2);
    }

    public function totalWithdraw($days = null)
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->orWhere('type', TxnType::WithdrawAuto);
        });
        if (null != $days) {
            $sum->where('created_at', '>=', Carbon::now()->subDays((int) $days));
        }
        $sum = $sum->sum('amount');
        return round($sum, 2);
    }


    public function rank()
    {
        return $this->belongsTo(Ranking::class, 'ranking_id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function rankAchieved()
    {
        return count(json_decode($this->rankings, true));
    }

    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value != null ? decrypt($value) : $value,
            set: fn ($value) => encrypt($value),
        );
    }
    
    public function scopeApplyFilters(Builder $query, $filters)
    {
        if (!empty($filters['global_search'])) {
            $search = $filters['global_search'];
            $query->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', "%" . $filters['phone'] . "%");
        }

        if (!empty($filters['country'])) {
            $query->where('country', 'like', "%" . $filters['country'] . "%");
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (!empty($filters['tag'])) {
            $query->where('comment', 'like', "%" . $filters['tag'] . "%");
        }

        return $query;
    }
}
