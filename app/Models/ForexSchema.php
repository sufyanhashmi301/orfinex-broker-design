<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ForexSchema
 *
 * @property int $id
 * @property string|null $icon
 * @property string $title
 * @property string|null $desc
 * @property int $priority
 * @property string|null $badge
 * @property string|null $spread
 * @property string|null $commission
 * @property string $leverage
 * @property float|null $first_min_deposit
 * @property int $account_limit
 * @property int|null $start_range
 * @property int|null $end_range
 * @property int $is_weekend_holding
 * @property int $is_scalable
 * @property int $is_refundable
 * @property int $status
 * @property string|null $country
 * @property string|null $tags
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|MultiLevel[] $multi_levels
 *
 * @package App\Models
 */
class ForexSchema extends Model
{
	protected $table = 'forex_schemas';

	protected $casts = [
		'priority' => 'int',
		'first_min_deposit' => 'float',
		'account_limit' => 'int',
		'start_range' => 'int',
		'end_range' => 'int',
		'is_weekend_holding' => 'int',
		'is_scalable' => 'int',
		'is_refundable' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'icon',
		'title',
		'desc',
		'priority',
		'badge',
		'spread',
		'commission',
		'leverage',
		'first_min_deposit',
		'account_limit',
		'start_range',
		'end_range',
		'is_weekend_holding',
		'is_scalable',
		'is_refundable',
		'status',
		'country',
		'tags',
        'upto_allotted_fund',
        'upto_profit_target',
        'upto_daily_max_loss',
        'upto_maximum_loss'
	];

    public function forexAccounts()
    {
        return $this->hasMany(ForexAccount::class);
    }
    public function forexSchemaPhases()
    {
        return $this->hasMany(ForexSchemaPhase::class);
    }
    public function forexSchemaPhase1()
    {
        return $this->hasOne(ForexSchemaPhase::class)->where('phase',1);
    }

    public function multiLevels()
    {
        return $this->hasMany(MultiLevel::class,'forex_scheme_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }
    public function scopeRelevantForUser(Builder $query, $country, array $tags)
    {
        return $query->where(function($q) use ($country, $tags) {
            $q->whereJsonContains('country', $country)
                ->orWhereJsonContains('country', 'All')
                ->orWhere(function($subQuery) use ($tags) {
                    foreach ($tags as $tag) {
                        $subQuery->orWhereJsonContains('tags', $tag);
                    }
                });
        });
    }
}
