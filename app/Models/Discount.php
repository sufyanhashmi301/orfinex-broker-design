<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 *
 * @property int $id
 * @property string $code_name
 * @property string $code
 * @property string|null $scheme_type
 * @property string $type
 * @property string|null $applied_to
 * @property int $usage_limit
 * @property int $used_count
 * @property float|null $percentage
 * @property float|null $fixed_amount
 * @property Carbon|null $expire_at
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Discount extends Model
{
	protected $table = 'discounts';

	protected $casts = [
		'usage_limit' => 'int',
		'used_count' => 'int',
		'applied_to' => 'array',
		'discount_levels' => 'array',
		'percentage' => 'float',
		'fixed_amount' => 'float',
		'status' => 'bool'
	];

	protected $fillable = [
		'code_name',
		'code',
		'scheme_type',
		'type',
		'applied_to',
		'discount_levels',
		'usage_limit',
		'used_count',
		'percentage',
		'fixed_amount',
		'expire_at',
		'status'
	];
}
