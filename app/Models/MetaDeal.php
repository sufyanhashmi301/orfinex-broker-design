<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MetaDeal
 *
 * @property int $id
 * @property int $user_id
 * @property int $login
 * @property int $deal
 * @property int $dealer
 * @property int $order
 * @property string $symbol
 * @property int $volume
 * @property int $volume_closed
 * @property float $lot_share
 * @property Carbon $time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class MetaDeal extends Model
{
	protected $table = 'meta_deals';

	protected $casts = [
		'user_id' => 'int',
		'login' => 'int',
		'deal' => 'int',
		'dealer' => 'int',
		'order' => 'int',
		'volume' => 'int',
		'volume_closed' => 'int',
		'lot_share' => 'float',
		'time' => 'datetime',
		'is_paid' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'login',
		'deal',
		'dealer',
		'order',
		'symbol',
		'volume',
		'volume_closed',
		'lot_share',
		'time',
		'is_paid',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
    // Accessor to convert BigDecimal to float
    public function getLotShareAttribute($value)
    {
        return $value instanceof BigDecimal ? $value->toFloat() : (float) $value;
    }
}
