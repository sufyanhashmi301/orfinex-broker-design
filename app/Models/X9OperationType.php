<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class X9OperationType
 * 
 * @property int $id
 * @property int $operation_id
 * @property string $name
 * @property int $operation_type_id
 * @property string $operation_type_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class X9OperationType extends Model
{
	protected $table = 'x9_operation_types';

	protected $casts = [
		'operation_id' => 'int',
		'operation_type_id' => 'int'
	];

	protected $fillable = [
		'operation_id',
		'name',
		'operation_type_id',
		'operation_type_name'
	];
}
