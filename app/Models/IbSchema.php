<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IbSchema
 * 
 * @property int $id
 * @property string|null $icon
 * @property string $title
 * @property string|null $desc
 * @property string|null $badge
 * @property string|null $type
 * @property string|null $group
 * @property int|null $priority
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class IbSchema extends Model
{
	protected $table = 'ib_schemas';

	protected $casts = [
		'priority' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'icon',
		'title',
		'desc',
		'badge',
		'type',
		'group',
		'priority',
		'status'
	];
}
