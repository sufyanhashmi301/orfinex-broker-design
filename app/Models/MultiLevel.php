<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MultiLevel
 *
 * @property int $id
 * @property int $forex_scheme_id
 * @property string $type
 * @property string $title
 * @property int $level_order
 * @property string $group_tag
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property ForexSchema $forex_schema
 *
 * @package App\Models
 */
class MultiLevel extends Model
{
	protected $table = 'multi_levels';

	protected $casts = [
		'forex_scheme_id' => 'int',
		'level_order' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'forex_scheme_id',
		'type',
		'title',
		'level_order',
		'group_tag',
		'description',
		'status'
	];

	public function forexSchema()
	{
		return $this->belongsTo(ForexSchema::class, 'forex_scheme_id');
	}

    public function rebateRule()
    {
        return $this->belongsToMany(RebateRule::class)->withTimestamps();
    }
	public function ibGroups()
    {
        return $this->belongsToMany(IbGroup::class, 'ib_group_multi_level', 'multi_level_id', 'ib_group_id')->withTimestamps();
    }
}
