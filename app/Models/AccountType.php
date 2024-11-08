<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountType extends Model
{
    use HasFactory;

    protected $fillable = [
		'trader_type',
		'icon',
		'title',
		'platform_group',
		'type',
		'description',
		'priority',
		'badge',
		'spread',
		'commission',
		'leverage',
		'accounts_limit',
		'accounts_range_start',
		'accounts_range_end',
		'trading_days',
		'is_weekend_holding',
		'is_scalable',
		'is_refundable',
		'status',
		'countries',
		'tags',
		'upto_allotted_fund',
		'upto_profit_target',
		'upto_daily_max_loss',
		'upto_maximum_loss'
	];

	public function accountTypePhases(){
			return $this->hasMany(AccountTypePhase::class);
	}
	
	public function scopeActive(Builder $query)
	{
			return $query->where('status', true);
	}

	// will be deleted soon
	public function forexSchemaPhase1()
	{
			return $this->hasOne(AccountTypePhase::class)->where('phase_step', 1);
	}

	public function scopeTraderType(Builder $query)
	{
		return $query->where('trader_type', setting('active_trader_type', 'features'));
	}

	public function scopeRelevantForUser(Builder $query, $country, array $tags)
	{
			return $query->where(function($q) use ($country, $tags) {
					$q->whereJsonContains('countries', $country)
							->orWhereJsonContains('countries', 'All')
							->orWhere(function($subQuery) use ($tags) {
									foreach ($tags as $tag) {
											$subQuery->orWhereJsonContains('tags', $tag);
									}
							});
			});
	}

}
