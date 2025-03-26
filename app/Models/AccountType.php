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
		'offer_uuid',
		'system_uuid',
		'platform_group',
		'type',
		'description',
		'priority',
		'badge',
		'leverage',
		'accounts_limit',
		'accounts_range_start',
		'accounts_range_end',
		'profit_share',
		'trading_days',
		'is_trial',
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

	public function phaseOne()
	{
			return $this->hasOne(AccountTypePhase::class)->where('phase_step', 1);
	}
	
	public function scopeActive(Builder $query)
	{
			return $query->where('status', true);
	}
	// will be deleted soon
	

	public function scopeTraderType(Builder $query)
	{
		return $query->where('trader_type', setting('active_trader_type', 'features'));
	}

	public function scopeRelevantForUser(Builder $query, $country)
	{
			return $query->where(function($q) use ($country) {
					$q->whereJsonContains('countries', $country)
						->orWhereJsonContains('countries', 'All');
			});
	}

}
