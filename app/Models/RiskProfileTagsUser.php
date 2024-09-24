<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RiskProfileTagsUser
 * 
 * @property int $id
 * @property int $user_id
 * @property int $risk_profile_tag_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property RiskProfileTag $risk_profile_tag
 * @property User $user
 *
 * @package App\Models
 */
class RiskProfileTagsUser extends Model
{
	protected $table = 'risk_profile_tag_user';

	protected $casts = [
		'user_id' => 'int',
		'risk_profile_tag_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'risk_profile_tag_id'
	];

	public function risk_profile_tag()
	{
		return $this->belongsTo(RiskProfileTag::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
