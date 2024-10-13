<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class X9ClientGroup
 * 
 * @property int $id
 * @property int $client_group_type_id
 * @property string $name
 * @property string $currency
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property X9ClientGroupType $x9_client_group_type
 *
 * @package App\Models
 */
class X9ClientGroup extends Model
{
	protected $table = 'x9_client_groups';

	protected $casts = [
		'client_group_type_id' => 'int'
	];

	protected $fillable = [
		'client_group_type_id',
		'name',
		'currency',
		'type'
	];

	public function x9_client_group_type()
	{
		return $this->belongsTo(X9ClientGroupType::class, 'client_group_type_id');
	}
}
