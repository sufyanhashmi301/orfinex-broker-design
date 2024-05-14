<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FundedDiscountCode
 * 
 * @property int $id
 * @property string $code
 * @property float $discount
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class FundedDiscountCode extends Model
{
	protected $table = 'funded_discount_codes';

	protected $casts = [
		'discount' => 'float'
	];

	protected $fillable = [
		'code',
		'discount',
		'status'
	];
}
