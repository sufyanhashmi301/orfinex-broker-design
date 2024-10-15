<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class X9ClientGroupType
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_visible
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class X9ClientGroupType extends Model
{
	protected $table = 'x9_client_group_types';

	protected $casts = [
		'is_visible' => 'bool'
	];

	protected $fillable = [
		'name',
		'description',
		'is_visible'
	];
}
