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
 * @property int $multi_level_id
 * @property float $share
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
		'multi_level_id' => 'int',
		'share' => 'float'
	];

	protected $fillable = [
		'user_id',
		'multi_level_id',
		'share'
	];
    public function multiLevel()
    {
        return $this->belongsTo(MultiLevel::class,'multi_level_id');
    }
}
