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
		'trading_platform_title_format',
		'offer_uuid',
		'system_uuid',
		'platform_group',
		'type',
		'description',
		'priority',
		'badge',
		'leverage',
		'accounts_limit',
		'profit_share',
		'trading_days',
		'is_trial',
		'status',
		'cta_button_text',
		'countries',
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
