<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserIbRule
 *
 * @property int $id
 * @property int $user_id
 * @property int $rebate_rule_id
 * @property float $sub_ib_share
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserIbRule extends Model
{
	protected $table = 'user_ib_rules';

	protected $casts = [
		'user_id' => 'int',
		'ib_group_id' => 'int',
		'rebate_rule_id' => 'int',
		'sub_ib_share' => 'float'
	];

	protected $fillable = [
		'user_id',
		'ib_group_id',
		'rebate_rule_id',
		'sub_ib_share'
	];


    public function rebateRule()
    {
        return $this->belongsTo(RebateRule::class, 'rebate_rule_id');
    }

    /**
     * Belongs to a specific IB Group.
     */
    public function ibGroup()
    {
        return $this->belongsTo(IbGroup::class, 'ib_group_id');
    }

    /**
     * Belongs to a specific User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
