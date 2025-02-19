<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\ForexAccountStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ForexAccount
 *
 * @property int $id
 * @property int $user_id
 * @property int $forex_schema_id
 * @property string $account_name
 * @property string|null $login
 * @property string $account_type
 * @property string $trading_platform
 * @property string $group
 * @property string $currency
 * @property float $leverage
 * @property float $balance
 * @property float $credit
 * @property float $equity
 * @property float $free_margin
 * @property string|null $server
 * @property string|null $agent
 * @property string $meta
 * @property string|null $status
 * @property string|null $created_by
 * @property int $first_min_deposit_paid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ForexAccount extends Model
{
	use SoftDeletes;
	protected $table = 'forex_accounts';
    protected $guarded = ['id'];
	protected $casts = [
		'user_id' => 'int',
		'forex_schema_id' => 'int',
		'leverage' => 'float',
		'balance' => 'float',
		'credit' => 'float',
		'equity' => 'float',
		'free_margin' => 'float',
		'first_min_deposit_paid' => 'int',

	];

	protected $fillable = [
		'trader_type',
		'user_id',
		'forex_schema_id',
		'account_name',
		'login',
		'account_type',
		'trading_platform',
		'group',
		'currency',
		'leverage',
		'balance',
		'credit',
		'equity',
		'free_margin',
		'server',
		'agent',
		'meta',
		'status',
		'created_by',
		'first_min_deposit_paid'
	];

    public function schema()
    {
        return $this->hasOne(ForexSchema::class, 'id', 'forex_schema_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'target_id','login');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y H:i', strtotime($value));
    }
    public function scopeApplyFilters(Builder $query, $filters)
    {
        if (!empty($filters['global_search'])) {
            $search = $filters['global_search'];
            $query->where(function($query) use ($search) {
                $query->where('account_name', 'like', "%{$search}%")
                    ->orWhere('login', 'like', "%{$search}%")
                    ->orWhere('group', 'like', "%{$search}%")
                    ->orWhere('trading_platform', 'like', "%{$search}%")
                    ->orWhereHas('user', function($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                            //->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['login'])) {
            $query->where('login', 'like', "%" . $filters['login'] . "%");
        }

        if (!empty($filters['country'])) {
            $query->whereHas('user', function($query) use ($filters) {
                $query->where('country', 'like', "%" . $filters['country'] . "%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

//        if (!empty($filters['tag'])) {
//            $query->where('meta', 'like', "%" . $filters['tag'] . "%");
//        }

        return $query;
    }
    public function scopeRealActiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('account_type','real')
            ->where('status', ForexAccountStatus::Ongoing);
    }
    public function scopeTraderType(Builder $query)
    {
        return $query->where('trader_type', setting('active_trader_type', 'features'));
    }
    public function scopeDemoActiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('account_type', 'demo')
            ->where('status', ForexAccountStatus::Ongoing);
    }
    public function scopeArchiveAccount($query,$userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        return $query->where('user_id', $userID)
            ->where('status', ForexAccountStatus::Archive);
    }


    public function leverageUpdates()
    {
        return $this->hasMany(LeverageUpdate::class, 'forex_account_id');
    }

}
