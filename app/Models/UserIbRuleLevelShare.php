<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserIbRuleLevelShare
 *
 * @property int $id
 * @property int $user_ib_rule_level_id
 * @property int $level_id
 * @property float $share
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserIbRuleLevelShare extends Model
{
	protected $table = 'user_ib_rule_level_shares';

	protected $casts = [
		'user_ib_rule_level_id' => 'int',
		'level_id' => 'int',
		'share' => 'float'
	];

	protected $fillable = [
		'user_ib_rule_level_id',
		'level_id',
		'share'
	];
}
