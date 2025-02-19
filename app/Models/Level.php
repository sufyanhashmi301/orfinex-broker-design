<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Level
 * 
 * @property int $id
 * @property string $title
 * @property int $level_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Level extends Model
{
	protected $table = 'levels';

	protected $casts = [
		'level_order' => 'int'
	];

	protected $fillable = [
		'title',
		'level_order'
	];
}
