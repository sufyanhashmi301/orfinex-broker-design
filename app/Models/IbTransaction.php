<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IbTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property string $deal
 * @property string $login
 * @property string $profit
 * @property string $client_no
 * @property string $trade_id
 * @property string $level_share
 * @property Carbon $process_time
 * @property Carbon $calc_at
 * @property Carbon|null $clear_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class IbTransaction extends Model
{
	protected $table = 'ib_transactions';

	protected $casts = [
		'user_id' => 'int',
		'process_time' => 'datetime',
		'calc_at' => 'datetime',
		'clear_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'deal',
		'login',
		'profit',
		'client_no',
		'trade_id',
		'level_share',
		'process_time',
		'calc_at',
		'clear_at'
	];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
