<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ForexSchemaPhaseRule
 *
 * @property int $id
 * @property int $forex_schema_phase_id
 * @property string $unique_id
 * @property float $amount
 * @property float $fee
 * @property float $discount
 * @property string|null $currency
 * @property float $total
 * @property float $allotted_funds
 * @property float $daily_drawdown_limit
 * @property float $max_drawdown_limit
 * @property float $profit_target
 * @property float $profit_share_user
 * @property float $profit_share_admin
 * @property int $is_new_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property ForexSchemaPhase $forex_schema_phase
 *
 * @package App\Models
 */
class ForexSchemaPhaseRule extends Model
{
	use SoftDeletes;
	protected $table = 'forex_schema_phase_rules';

	protected $casts = [
		'forex_schema_phase_id' => 'int',
		'amount' => 'float',
		'fee' => 'float',
		'discount' => 'float',
		'total' => 'float',
		'allotted_funds' => 'float',
		'daily_drawdown_limit' => 'float',
		'max_drawdown_limit' => 'float',
		'profit_target' => 'float',
		'profit_share_user' => 'float',
		'profit_share_admin' => 'float',
		'is_new_order' => 'int'
	];

	protected $fillable = [
		'forex_schema_phase_id',
		'unique_id',
		'amount',
		'fee',
		'discount',
		'currency',
		'total',
		'allotted_funds',
		'daily_drawdown_limit',
		'max_drawdown_limit',
		'profit_target',
		'profit_share_user',
		'profit_share_admin',
		'is_new_order'
	];

	public function forexSchemaPhase()
	{
		return $this->belongsTo(ForexSchemaPhase::class);
	}
}
