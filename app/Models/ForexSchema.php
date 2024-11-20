<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
		'trader_type',
		'bonus_id',
		'icon',
		'title',
		'desc',
		'badge',
		'badge',
		'leverage',
		'first_min_deposit',
		'min_amount',
		'account_limit',
		'real_swap_free',
		'is_real_islamic',
		'real_islamic',
		'demo_swap_free',
		'is_demo_islamic',
		'demo_islamic',
		'is_withdraw',
		'is_ib_partner',
		'ib_group_id',
		'is_internal_transfer',
		'is_external_transfer',
		'is_bonus',
		'start_range',
		'end_range',
		'status',
		'country',
		'tags',
		'spread',
		'commission',
		'priority',
		'demo_server',
		'live_server',
	];

	public function forexAccounts()
	{
		return $this->hasMany(ForexAccount::class);
	}
    public function ibGroup()
    {
        return $this->belongsTo(IbGroup::class, 'ib_group_id', 'id');
    }

	public function bonuses(){
			return $this->belongsToMany(Bonus::class);
	}


    public function scopeTraderType(Builder $query)
    {
        return $query->where('trader_type', setting('active_trader_type', 'features'));
    }
	public function multiLevels()
	{
		return $this->hasMany(MultiLevel::class, 'forex_scheme_id');
	}

	public function scopeActive(Builder $query)
	{
		return $query->where('status', true);
	}
	public function scopeRelevantForUser(Builder $query, $country, array $tags)
	{
		return $query->where(function ($q) use ($country, $tags) {
			$q->whereJsonContains('country', $country)
				->orWhereJsonContains('country', 'All')
				->orWhere(function ($subQuery) use ($tags) {
					foreach ($tags as $tag) {
						$subQuery->orWhereJsonContains('tags', $tag);
					}
				});
		});
	}
}
