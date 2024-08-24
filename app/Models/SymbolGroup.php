<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SymbolGroup
 *
 * @property int $id
 * @property string $title
 * @property string $platform_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|RebateRule[] $rebate_rules
 * @property Collection|Symbol[] $symbols
 *
 * @package App\Models
 */
class SymbolGroup extends Model
{
	protected $table = 'symbol_groups';

	protected $fillable = [
		'title',
		'platform_type'
	];

	public function rebateRule()
	{
		return $this->belongsToMany(RebateRule::class)
					->withPivot('id')
					->withTimestamps();
	}

	public function symbols()
	{
		return $this->belongsToMany(Symbol::class)
					->withTimestamps();
	}
}
