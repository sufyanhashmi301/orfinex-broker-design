<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RebateRecord
 * 
 * @property int $id
 * @property int $user_id
 * @property int $login
 * @property int|null $deal
 * @property string|null $symbol
 * @property float $amount
 * @property float $rebate_amount
 * @property float $final_amount
 * @property string $currency
 * @property Carbon $record_at
 * @property Carbon|null $cleared_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RebateRecord extends Model
{
	protected $table = 'rebate_records';

	protected $casts = [
		'user_id' => 'int',
		'login' => 'int',
		'deal' => 'int',
		'amount' => 'float',
		'rebate_amount' => 'float',
		'final_amount' => 'float',
		'record_at' => 'datetime',
		'cleared_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'login',
		'deal',
		'symbol',
		'amount',
		'rebate_amount',
		'final_amount',
		'currency',
		'record_at',
		'cleared_at'
	];
}
