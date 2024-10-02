<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\InterestPeriod;
use App\Enums\InterestRateType;
use App\Enums\InvestmentStatus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ForexSchemaInvestment
 *
 * @property string $pvx
 * @property int $user_id
 * @property int $forex_schema_phase_rule_id
 * @property string|null $account_name
 * @property string|null $login
 * @property string|null $group
 * @property float $current_balance
 * @property float $current_equity
 * @property string|null $account_type
 * @property float $amount
 * @property float $amount_allotted
 * @property float $discount
 * @property float $leverage_amount
 * @property float $profit_split_amount
 * @property float $total
 * @property float $profit
 * @property float $received
 * @property string $currency
 * @property float $swap_free_amount
 * @property int $term_count
 * @property int $term_total
 * @property int|null $term_calc
 * @property Carbon $term_start
 * @property Carbon|null $term_end
 * @property string|null $scheme
 * @property string|null $meta
 * @property string|null $desc
 * @property string|null $platform
 * @property int $leverage
 * @property string $days_to_pass
 * @property int $profit_share_user
 * @property int $profit_share_admin
 * @property string|null $payouts
 * @property float $max_drawdown_limit
 * @property float $daily_drawdown_limit
 * @property float $snap_balance
 * @property float $snap_equity
 * @property float $snap_floating
 * @property float $max_balance
 * @property string|null $main_password
 * @property string|null $invest_password
 * @property string|null $phone_password
 * @property string $status
 * @property string|null $drawdown_reason
 * @property string|null $pay_from
 * @property string|null $pay_detail
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ForexSchemaInvestment extends Model
{
	protected $table = 'forex_schema_investments';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'forex_schema_phase_rule_id' => 'int',
		'current_balance' => 'float',
		'current_equity' => 'float',
		'amount' => 'float',
		'amount_allotted' => 'float',
		'discount' => 'float',
		'leverage_amount' => 'float',
		'profit_split_amount' => 'float',
		'total' => 'float',
		'profit' => 'float',
		'received' => 'float',
		'swap_free_amount' => 'float',
		'weekly_payout_amount' => 'float',
		'term_count' => 'int',
		'term_total' => 'int',
		'term_calc' => 'int',
		'term_start' => 'datetime',
		'term_end' => 'datetime',
		'leverage' => 'int',
		'profit_share_user' => 'int',
		'profit_share_admin' => 'int',
		'max_drawdown_limit' => 'float',
		'daily_drawdown_limit' => 'float',
		'snap_balance' => 'float',
		'snap_equity' => 'float',
		'snap_floating' => 'float',
        'scheme' => 'array',
        'meta' => 'array',
		'max_balance' => 'float'
	];

	protected $hidden = [
		'main_password',
		'invest_password',
		'phone_password'
	];

	protected $fillable = [
		'pvx',
		'trader_type',
		'user_id',
		'forex_schema_phase_rule_id',
		'account_name',
		'login',
		'group',
		'current_balance',
		'current_equity',
		'account_type',
		'amount',
		'amount_allotted',
		'discount',
		'weekly_payout_amount',
        'swap_free_amount',
        'leverage_amount',
		'profit_split_amount',
		'total',
		'profit',
		'received',
		'currency',
		'term_count',
		'term_total',
		'term_calc',
		'term_start',
		'term_end',
		'scheme',
		'meta',
		'desc',
		'platform',
		'leverage',
		'days_to_pass',
		'profit_share_user',
		'profit_share_admin',
		'payouts',
		'max_drawdown_limit',
		'daily_drawdown_limit',
		'snap_balance',
		'snap_equity',
		'snap_floating',
		'equity_cal_at',
		'max_balance',
		'main_password',
		'invest_password',
		'phone_password',
		'status',
		'qualify_stage',
		'drawdown_reason',
		'violated_at',
		'pay_from',
		'pay_detail',
		'daily_score_record_at',
		'weekly_score_record_at',
		'total_score_record_at',
	];


    public function forexSchemaPhaseRule()
    {
        return $this->belongsTo(ForexSchemaPhaseRule::class,'forex_schema_phase_rule_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pricing_scheme()
    {
        return $this->belongsTo(PricingScheme::class,'pricing_scheme_id');
    }
    public function ledgers()
    {
        return $this->hasMany(IvLedger::class, 'invest_id')
            ->orderBy('iv_ledgers.created_at', 'asc')
            ->orderBy('iv_ledgers.id', 'asc');
    }

    public function ledger()
    {
        return $this->hasOne(IvLedger::class, 'reference', 'ivx');
    }

    public function actions()
    {
        return $this->hasMany(IvAction::class, 'type_id')->where('type', 'invest');
    }
    public function scopeSumAmountAllotted($query)
    {
        return $query->select($query->raw('SUM(amount_allotted) as amount_allotted'));
    }

    public function action_by($type)
    {
        $action = $this->actions->where('action', $type)->last();

        if(!blank($action)) {
            return $action;
        }
        return false;
    }

    public function get_action($type, $what=null)
    {
        $action = $this->action_by($type);
        $what = ($what=='by') ? 'action_by' : 'action_at';

        if(!empty($action)) {
            return data_get($action, $what, false);
        }
        return false;
    }

    public function scopeWithTotalProfit($query)
    {
        $query->select('user_id', DB::raw('SUM(profit) as total_profit'),DB::raw('SUM(payout_amount) as payout_amount'), DB::raw('SUM(amount_allotted) as total_amount_allotted'))
            ->groupBy('user_id');
    }
    public function scopeWithHighestProfit($query, $limit = 5)
    {
        $query->withTotalProfit()
            ->orderByDesc('total_profit')
            ->limit($limit);
    }
    public function scopeWithHighestPayout($query, $limit = 5)
    {
        $query->withTotalProfit()
            ->orderByDesc('payout_amount')
            ->limit($limit);
    }
    public function scopeSumPayout($query)
    {
        return $query->select($query->raw('SUM(payout_amount) as total_payout_amount'));
    }

    public function scopeTraderType($query)
    {
        return $query->where('trader_type', setting('active_trader_type', 'features'));
    }
    public function scopeLoggedUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function getSummaryTitleAttribute()
    {
        return sprintf(
            '%s - %d %s',
            data_get($this->scheme, 'name'),
//            ucfirst(data_get($this->scheme, 'calc_period')),
//            data_get($this->scheme, 'min_rate'),
//            data_get($this->scheme, 'rate'),
//            data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT ? '%' : ' '.strtoupper($this->currency),
//            data_get($this->scheme, 'term'),
            ucfirst(data_get($this, 'amount_allotted')),
            data_get($this, 'currency'),
//            data_get($this, 'login')
        );
    }

    public function getCalcDetailsAttribute()
    {
        return sprintf(
            '%s for  %s',
            ucfirst(data_get($this->scheme, 'calc_period')),
//            data_get($this->scheme, 'rate'),
//            data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT ? '%' : ' '.strtoupper($this->currency),
//            data_get($this->scheme, 'term'),
            ucfirst(data_get($this->scheme, 'term_type'))
        );
    }

    public function getOrderAtAttribute()
    {
        return $this->get_action('order', 'at');
    }

    public function getOrderByAttribute()
    {
        return $this->get_action('order', 'by');
    }

    public function getApproveAtAttribute()
    {
        return $this->get_action('active', 'at');
    }

    public function getApproveByAttribute()
    {
        return $this->get_action('active', 'by');
    }

    public function getCompletedAtAttribute()
    {
        return $this->get_action('complete', 'at');
    }

    public function getCompletedByAttribute()
    {
        return $this->get_action('complete', 'by');
    }

    public function getCancelledAtAttribute()
    {
        return $this->get_action('cancel', 'at');
    }

    public function getCancelledByAttribute()
    {
        return $this->get_action('cancel', 'by');
    }

    public function getPaymentSourceAttribute()
    {
        return data_get($this, 'meta.source');
//        return data_get($this->ledger, 'source');
    }

    public function getPaymentDestAttribute()
    {
        return data_get($this->ledger, 'dest');
    }

    public function getPaymentDateAttribute()
    {
        return show_date(data_get($this->ledger, 'created_at'), true);
    }

    public function getPaidAmountAttribute()
    {
        return data_get($this->ledger, 'total');
    }

    public function getProfitLockedAttribute()
    {
        return $this->profits->whereNull('payout')->sum('amount');
    }

    public function getPendingAmountAttribute()
    {
        return ($this->total - $this->received);
    }

    public function getCodeAttribute()
    {
        $shortname = data_get($this, 'scheme.short');

        return substr($shortname, 0, 2);
    }

    public function getRateTextAttribute()
    {
        $currency = base_currency();
        if(data_get($this->scheme, 'rate_type') == InterestRateType::FIXED) {
            return $currency. ' '.amount_z(data_get($this->scheme, 'rate'), $currency);
        }

        return data_get($this->scheme, 'rate') . '%';
    }

    public function getCalcProfitAttribute()
    {
        $profit = (data_get($this->scheme, 'rate_type') == InterestRateType::FIXED) ? data_get($this->scheme, 'rate')  : ($this->amount * data_get($this->scheme, 'rate') / 100);

        if (data_get($this, 'scheme.capital') == 0) {
            $capital = ($this->amount / $this->term_total);
            return ($profit + $capital);
        }

        return $profit;
    }

    public function getPeriodTextAttribute()
    {
        $calcPeriod = data_get($this, 'scheme.calc_period');

        switch ($calcPeriod) {
            case InterestPeriod::HOURLY:
                return __('Per Hour');
            case InterestPeriod::DAILY:
                return __('Per Day');
            case InterestPeriod::WEEKLY:
                return __('Per Week');
            case InterestPeriod::MONTHLY:
                return __('Per Month');
            case InterestPeriod::YEARLY:
                return __('Per Year');
        }
    }

    public function getCalcPeriodAttribute()
    {
        $calcPeriod = data_get($this, 'scheme.calc_period');
        return str_replace(__('Per '), '', $this->getPeriodTextAttribute());
    }

    public function getProgressAttribute()
    {
        $percent = 0;
        if (!empty($this->term_count) && !empty($this->term_total)) {
            $percent = round((($this->term_count/$this->term_total) * 100), 2);
        }

        return ($percent > 99) ? 100 : $percent;
    }


    private function calculateIntervals($tillNow = false)
    {
        if (empty($this->term_start) || empty($this->term_end)) {
            return [];
        }
        $end = ($tillNow) ? (Carbon::now()->gt($this->term_end) ? $this->term_end : Carbon::now()) : $this->term_end;
        $interval = $this->interval[data_get($this, 'scheme.calc_period')];
        $intervalPeriod = CarbonPeriod::create($this->term_start, $interval, $end)->toArray();
        array_shift($intervalPeriod);

        // Limited if Interval over than term count.
        if(count($intervalPeriod) >= $this->term_total) {
            $intervalPeriod = array_slice($intervalPeriod, 0, $this->term_total);
        }
        return $intervalPeriod;
    }

    public function getTotalPeriodIntervalAttribute()
    {
        return $this->calculateIntervals();
    }

    public function getPeriodIntervalElapsedAttribute()
    {
        return $this->calculateIntervals(true);
    }

    public function getRemainingPeriodIntervalCountAttribute()
    {
        return (count($this->getTotalPeriodIntervalAttribute()) - count($this->getPeriodIntervalElapsedAttribute()));
    }

    public function getRemainingTermAttribute()
    {
        $remain = ($this->term_total - $this->term_count);

        return ($remain > 0) ? $remain : 0;
    }

    public function getRemainingPeriodTextAttribute(): string
    {
        return $this->getRemainingPeriodIntervalCountAttribute() . ' ' . strtoupper(substr(data_get($this, 'scheme.calc_period'), 0, 1));
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', InvestmentStatus::ACTIVE);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    public function scopeSumProfit($query)
    {
        return $query->sum('profit');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfMonth()->tz(time_zone()),
            Carbon::now()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfWeek()->tz(time_zone()),
            Carbon::now()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeLastWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subWeek()->startOfWeek()->tz(time_zone()),
            Carbon::now()->subWeek()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeFromLastWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subWeek()->startOfWeek()->tz(time_zone()),
            Carbon::now()->tz(time_zone())
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subMonth()->startOfMonth()->tz(time_zone()),
            Carbon::now()->subMonth()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfYear()->tz(time_zone()),
            Carbon::now()->endOfYear()->tz(time_zone())
        ]);
    }

    public function scopeIsValid($query)
    {
        return $query->where('status',InvestmentStatus::ACTIVE)
            ->orWhere('status',InvestmentStatus::COMPLETED);
    }

    public function scopeLastYear($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subYear()->startOfYear()->tz(time_zone()),
            Carbon::now()->subYear()->endOfYear()->tz(time_zone())
        ]);
    }

    public function scopeLastDays($query, $days)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subDays($days)->tz(time_zone()),
            Carbon::now()->tz(time_zone())
        ]);
    }

    public function getUserCanCancelAttribute(): bool
    {
        if ($this->status != InvestmentStatus::PENDING) {
            return false;
        }

        $cancelTimeout = sys_settings('iv_cancel_timeout', 15);
        $elapsedTime = $this->created_at->diffInMinutes(Carbon::now());
        if ($elapsedTime > $cancelTimeout) {
            return false;
        }

        return true;
    }
}
