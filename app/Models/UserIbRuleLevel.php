<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserIbRuleLevel
 *
 * @property int $id
 * @property int $level_id
 * @property int $user_ib_rule_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserIbRuleLevel extends Model
{
	protected $table = 'user_ib_rule_levels';

	protected $casts = [
		'level_id' => 'int',
		'user_ib_rule_id' => 'int'
	];

	protected $fillable = [
		'level_id',
		'user_ib_rule_id'
	];

    public function userIbRuleLevelShares()
    {
        return $this->hasMany(UserIbRuleLevelShare::class, 'user_ib_rule_level_id');
    }

}
