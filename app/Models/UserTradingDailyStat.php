<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTradingDailyStat
 * 
 * @property int $id
 * @property int $user_id
 * @property int $login
 * @property float $net_profit
 * @property float $highest_profit_trade
 * @property float $highest_lost_trade
 * @property int $total_trades
 * @property float $total_profit
 * @property float $total_losses
 * @property float $pnl_ratio
 * @property float $avg_trade_profit_per_loss
 * @property float $win_rate
 * @property float $loss_rate
 * @property float $avg_holding_time
 * @property float $total_deposits
 * @property float $total_withdrawals
 * @property float $withdrawal_rate
 * @property float $risk_reward_ratio
 * @property float $capital_retention_ratio
 * @property Carbon $stat_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserTradingDailyStat extends Model
{
	protected $table = 'user_trading_daily_stats';

	protected $casts = [
		'user_id' => 'int',
		'login' => 'int',
		'net_profit' => 'float',
		'highest_profit_trade' => 'float',
		'highest_lost_trade' => 'float',
		'total_trades' => 'int',
		'total_profit' => 'float',
		'total_losses' => 'float',
		'pnl_ratio' => 'float',
		'avg_trade_profit_per_loss' => 'float',
		'win_rate' => 'float',
		'loss_rate' => 'float',
		'avg_holding_time' => 'float',
		'total_deposits' => 'float',
		'total_withdrawals' => 'float',
		'withdrawal_rate' => 'float',
		'risk_reward_ratio' => 'float',
		'capital_retention_ratio' => 'float',
		'stat_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'login',
		'net_profit',
		'highest_profit_trade',
		'highest_lost_trade',
		'total_trades',
		'total_profit',
		'total_losses',
		'pnl_ratio',
		'avg_trade_profit_per_loss',
		'win_rate',
		'loss_rate',
		'avg_holding_time',
		'total_deposits',
		'total_withdrawals',
		'withdrawal_rate',
		'risk_reward_ratio',
		'capital_retention_ratio',
		'stat_date'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
