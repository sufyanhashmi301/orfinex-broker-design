<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RebateRule
 *
 * @property int $id
 * @property string $title
 * @property int $rule_type_id
 * @property float $rebate_amount
 * @property int $per_lot
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|MultiLevel[] $multi_levels
 * @property Collection|SymbolGroup[] $symbol_groups
 *
 * @package App\Models
 */
class RebateRule extends Model
{
	protected $table = 'rebate_rules';

	protected $casts = [
		'rule_type_id' => 'int',
		'rebate_amount' => 'float',
		'per_lot' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'title',
		'rule_type_id',
		'rebate_amount',
		'per_lot',
		'status'
	];
// App\Models\RebateRule.php

    public function forexSchemas()
    {
        return $this->belongsToMany(ForexSchema::class, 'forex_schema_rebate_rule', 'rebate_rule_id', 'forex_schema_id')->withTimestamps();
    }

	public function multiLevels()
	{
		return $this->belongsToMany(MultiLevel::class)
					->withTimestamps();
	}

	public function symbolGroups()
	{
		return $this->belongsToMany(SymbolGroup::class)
					->withPivot('id')
					->withTimestamps();
	}
    public function ibGroups()
    {
        // Ensure this matches the pivot table's structure
        return $this->belongsToMany(IbGroup::class, 'ib_group_rebate_rule', 'rebate_rule_id', 'ib_group_id');
    }


}
