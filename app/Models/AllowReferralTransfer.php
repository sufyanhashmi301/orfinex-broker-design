<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AllowReferralTransfer
 * 
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property Carbon $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AllowReferralTransfer extends Model
{
	protected $table = 'allow_referral_transfers';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'user_id',
		'type',
		'date'
	];
}
