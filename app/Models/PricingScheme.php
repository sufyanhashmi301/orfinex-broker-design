<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\SchemeStatus;
use App\Filters\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class PricingScheme
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $short
 * @property string|null $desc
 * @property float $amount
 * @property float $amount_allotted
 * @property int $leverage
 * @property string $days_to_pass
 * @property int $profit_share_user
 * @property int $profit_share_admin
 * @property string $payouts
 * @property string $term_type
 * @property string $calc_period
 * @property float $daily_drawdown_limit
 * @property float $total_drawdown_limit
 * @property string $status
 * @property string $is_highlighted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PricingScheme extends Model
{

	protected $table = 'pricing_schemes';

	protected $casts = [
		'amount' => 'float',
		'amount_allotted' => 'float',
		'leverage' => 'int',
		'profit_share_user' => 'int',
		'profit_share_admin' => 'int',
		'daily_drawdown_limit' => 'float',
		'max_drawdown_limit' => 'float'
	];

	protected $fillable = [
		'name',
		'slug',
		'short',
		'desc',
		'amount',
		'amount_allotted',
		'type',
		'sub_type',
		'leverage',
		'days_to_pass',
		'profit_share_user',
		'profit_share_admin',
		'profit_target',
		'payouts',
		'term_type',
		'calc_period',
		'daily_drawdown_limit',
		'max_drawdown_limit',
		'min_trading_days',
		'max_trading_days',
		'swap_group',
		'swap_free_group',
		'discount_price',
		'stage',
		'status',
		'is_highlighted',
		'approval',
		'ea_boat',
		'trading_news',
		're_attempt_discount',
		'weekend_holding',
		'refundable',
		'is_discount',
	];
    const NEXT_STATUSES = [
        SchemeStatus::ACTIVE => [
            SchemeStatus::INACTIVE,
            SchemeStatus::ARCHIVED,
        ],
        SchemeStatus::INACTIVE => [
            SchemeStatus::ACTIVE,
            SchemeStatus::ARCHIVED,
        ],
        SchemeStatus::ARCHIVED => [
            SchemeStatus::ACTIVE,
            SchemeStatus::INACTIVE,
        ]
    ];

    protected static function booted()
    {
        static::addGlobalScope('exceptArchived', function (Builder $builder) {
            $builder->where('status', '<>', SchemeStatus::ARCHIVED);
        });
    }
}
