<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypePhaseRule extends Model
{
    use HasFactory;

    protected $fillable = [
			'account_type_phase_id',
			'unique_id',
			'amount',
			'fee',
			'discount',
			'currency',
			'total',
			'allotted_funds',
			'daily_drawdown_limit',
			'max_drawdown_limit',
			'profit_target',
			'trading_days',
			'profit_share_user',
			'profit_share_admin',
			'is_new_order'
    ];

		public function accountTypePhase(){
				return $this->belongsTo(AccountTypePhase::class);
		}
}
