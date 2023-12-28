<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IbQuestion
 * 
 * @property int $id
 * @property string $name
 * @property string $fields
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class IbQuestion extends Model
{
	protected $table = 'ib_questions';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'fields',
		'status'
	];
}
