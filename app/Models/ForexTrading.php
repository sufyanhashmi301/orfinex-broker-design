<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\ForexTradingAccountTypesStatus;
use App\Enums\ForexTradingStatus;
use App\Enums\InterestRateType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filters\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class ForexTrading
 *
 * @property int $id
 * @property int $user_id
 * @property string $account_name
 * @property string|null $login
 * @property string $type
 * @property string $account_type
 * @property string $trading_platform
 * @property string $group
 * @property string|null $main_password
 * @property string|null $invest_password
 * @property string|null $phone_password
 * @property string $auth
 * @property float $leverage
 * @property float $free_margin
 * @property string $currency
 * @property float $balance
 * @property string|null $server
 * @property array $meta
 * @property string|null $status
 * @property Carbon|null $completed_at
 * @property array|null $created_by
 * @property int|null $completed_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ForexTrading extends Model
{
    use Notifiable, Filterable, SoftDeletes;

	protected $table = 'forex_tradings';

	protected $casts = [
		'user_id' => 'int',
		'leverage' => 'float',
		'free_margin' => 'float',
		'balance' => 'float',
		'meta' => 'json',
		'created_by' => 'json',
		'completed_by' => 'int'
	];

	protected $dates = [
		'completed_at'
	];

	protected $hidden = [
		'main_password',
		'invest_password',
		'phone_password'
	];

	protected $fillable = [
		'account_type_id',
		'user_id',
		'account_name',
		'login',
		'type',
		'account_type',
		'trading_platform',
		'group',
		'main_password',
		'invest_password',
		'phone_password',
		'auth',
		'leverage',
		'equity',
		'margin',
		'free_margin',
		'currency',
		'balance',
		'server',
		'meta',
		'status',
		'agent',
		'total_volume',
		'completed_at',
		'created_by',
		'completed_by'
	];
    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function deposit_transactions()
    {
        return $this->hasMany(Transaction::class,'account_to')
            ->where(function($query) {
                $query->where('type', TransactionType::FOREX_TRADING_DEPOSIT)
                    ->orWhere('type', TransactionType::FOREX_TRADING_DEPOSIT_DIRECT);
            })
            ->where('status', TransactionStatus::COMPLETED);

    }
    public function scopeRealActiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('account_type', ForexTradingAccountTypesStatus::REAL)
            ->where('status', ForexTradingStatus::ACTIVE);
    }
    public function scopeDemoActiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('account_type', ForexTradingAccountTypesStatus::DEMO)
            ->where('status', ForexTradingStatus::ACTIVE);
    }
    public function scopeArchiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('status', ForexTradingStatus::ARCHIVE);
    }
    public function getSummaryTitleAttribute()
    {
        return sprintf(
            '%s - %s %s-%s%s for %d %s',
            data_get($this->scheme, 'name'),
            ucfirst(data_get($this->scheme, 'calc_period')),
            data_get($this->scheme, 'min_rate'),
            data_get($this->scheme, 'rate'),
            data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT ? '%' : ' '.strtoupper($this->currency),
            data_get($this->scheme, 'term'),
            ucfirst(data_get($this->scheme, 'term_type'))
        );
    }
}
