<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KycSubLevel
 * 
 * @property int $id
 * @property int $kyc_level_id
 * @property string|null $name
 * @property string|null $description
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property KycLevel $kyc_level
 * @property Collection|Kyc[] $kycs
 *
 * @package App\Models
 */
class KycSubLevel extends Model
{
	protected $table = 'kyc_sub_levels';

	protected $casts = [
		'kyc_level_id' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'kyc_level_id',
		'name',
		'description',
		'status'
	];

	public function kyc_level()
	{
		return $this->belongsTo(KycLevel::class);
	}

	public function kycs()
	{
		return $this->hasMany(Kyc::class);
	}
}
