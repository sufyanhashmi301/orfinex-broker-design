<?php

namespace App\Models;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use Carbon\Carbon;
use Coderflex\LaravelTicket\Concerns\HasTickets;
use Coderflex\LaravelTicket\Contracts\CanUseTickets;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanUseTickets, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasTickets;

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
        'balance',
        'profit_balance',
        'status',
        'ib_login',
        'ib_balance',
        'ib_status',
        'multi_ib_login',
        'multi_ib_balance',
        'is_multi_ib',
        'multi_ib_calc_at',

        'kyc',
        'kyc_credential',
        'risk_profile_tags',
        'google2fa_secret',
        'two_fa',
        'deposit_status',
        'withdraw_status',
        'transfer_status',
        'ref_id',
        'password',
    ];

    protected $appends = [
        'full_name', 'kyc_time', 'kyc_type', 'total_profit','total_deposit','total_invest',
    ];

    protected $dates = ['kyc_time'];

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
    public function riskProfileTags()
    {
        return $this->belongsToMany(RiskProfileTag::class, 'risk_profile_tags_users');
    }
    public function getUpdatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['updated_at'])->format('M d Y h:i');
    }

    public function getFullNameAttribute(): string
    {
        $firstName = $this->attributes['first_name'] ?? 'fname';
        $lastName = $this->attributes['last_name'] ?? 'lname';

        return ucwords("{$firstName} {$lastName}");
//        return ucwords("{$this->attributes['first_name']} {$this->attributes['last_name']}");
    }

    public function getKycTypeAttribute(): string
    {
        return json_decode($this->attributes['kyc_credential'], true)['kyc_type_of_name'] ?? '';
    }

    public function getKycTimeAttribute(): string
    {
        return json_decode($this->attributes['kyc_credential'], true)['kyc_time_of_time'] ?? '';
    }

    public function getTotalProfitAttribute(): string
    {
        return $this->totalProfit();
    }

    public function getTotalDepositAttribute(): string
    {
        return $this->totalDeposit();
    }
    public function getTotalInvestAttribute(): string
    {
        return $this->totalInvestment();
    }
    public function user_metas()
    {
        return $this->hasMany(UserMeta::class);
    }
    public function ibQuestionAnswers()
    {
        return $this->hasOne(IbQuestionAnswer::class);
    }

    public function totalProfit($days = null)
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Referral)
                ->orWhere('type', TxnType::SignupBonus)
                ->orWhere('type', TxnType::Interest)
                ->orWhere('type', TxnType::Bonus);

        });

        if (null != $days) {
            $sum->where('created_at', '>=', Carbon::now()->subDays((int) $days));
        }
        $sum = $sum->sum('amount');

        return round($sum, 2);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
    public function realTradingAccounts()
    {
        return $this->hasMany(ForexAccount::class)->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing);

    }


    public function totalRoiProfit()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Interest);
        })->sum('amount');

        return round($sum, 2);
    }

    public function getReferrals()
    {
        return ReferralProgram::all()->map(function ($program) {
            return ReferralLink::getReferral($this, $program);
        });
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_id');
    }

    public function totalDeposit()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Deposit)
                ->orWhere('type', TxnType::ManualDeposit);
        })->sum('amount');

        return round($sum, 2);
    }

    public function totalInvestment()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Investment);
        })->sum('amount');

        return round($sum, 2);
    }

    public function totalDepositBonus()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('target_id', '!=', null)
                ->where('target_type', 'deposit')
                ->where('type', TxnType::Referral);
        })->sum('amount');

        return round($sum, 2);

    }

    public function totalInvestBonus()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('target_id', '!=', null)
                ->where('target_type', 'investment')
                ->where('type', TxnType::Referral);
        })->sum('amount');

        return round($sum, 2);
    }

    public function totalWithdraw()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->orWhere('type', TxnType::WithdrawAuto);
        })->sum('amount');

        return round($sum, 2);
    }

    public function totalTransfer()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })->sum('amount');

        return round($sum, 2);
    }

    public function totalReferralProfit()
    {
        $sum = $this->transaction()->where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Referral);
        })->sum('amount');

        return round($sum, 2);
    }
    public function totalForexBalance()
    {
        $sum = $this->realTradingAccounts()->sum('balance');

        return round($sum, 2);
    }
    public function totalForexEquity()
    {
        $sum = $this->realTradingAccounts()->sum('equity');

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
}
