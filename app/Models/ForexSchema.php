<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ForexSchema
 *
 * @property int $id
 * @property string|null $icon
 * @property string $title
 * @property string|null $desc
 * @property string|null $badge
 * @property string $leverage
 * @property float|null $first_min_deposit
 * @property string|null $real_swap_free
 * @property string|null $real_islamic
 * @property string|null $demo_swap_free
 * @property string|null $demo_islamic
 * @property int $is_withdraw
 * @property int $is_ib_partner
 * @property int $is_internal_transfer
 * @property int $is_external_transfer
 * @property int $is_bonus
 * @property int $status
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ForexSchema extends Model
{
	protected $table = 'forex_schemas';

	protected $casts = [
		'first_min_deposit' => 'float',
		'is_withdraw' => 'int',
		'is_ib_partner' => 'int',
		'is_internal_transfer' => 'int',
		'is_external_transfer' => 'int',
		'is_bonus' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'icon',
		'title',
		'desc',
		'badge',
		'leverage',
		'first_min_deposit',
		'real_swap_free',
		'real_islamic',
		'demo_swap_free',
		'demo_islamic',
		'is_withdraw',
		'is_ib_partner',
		'is_internal_transfer',
		'is_external_transfer',
		'is_bonus',
		'status',
		'country',
		'priority'
	];
}
