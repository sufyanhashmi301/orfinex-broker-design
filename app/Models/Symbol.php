<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Symbol
 *
 * @property int $id
 * @property int $symbol_id
 * @property string $symbol
 * @property string $path
 * @property string $description
 * @property string $contract_size
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|SymbolGroup[] $symbol_groups
 *
 * @package App\Models
 */
class Symbol extends Model
{
	protected $table = 'symbols';

	protected $casts = [
		'symbol_id' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'symbol_id',
		'symbol',
		'path',
		'description',
		'contract_size',
		'status'
	];

	public function symbolGroups()
	{
		return $this->belongsToMany(SymbolGroup::class)
					->withTimestamps();
	}
}
