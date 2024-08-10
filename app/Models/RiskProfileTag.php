<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RiskProfileTag
 * 
 * @property int $id
 * @property string $name
 * @property string|null $desc
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RiskProfileTag extends Model
{
	protected $table = 'risk_profile_tags';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'status'
	];
}
