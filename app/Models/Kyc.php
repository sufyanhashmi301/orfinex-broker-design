<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kyc
 * 
 * @property int $id
 * @property int|null $kyc_sub_level_id
 * @property string $name
 * @property string|null $fields
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property KycSubLevel|null $kyc_sub_level
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Kyc extends Model
{
	protected $table = 'kycs';

	protected $casts = [
		'kyc_sub_level_id' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'kyc_sub_level_id',
		'name',
		'fields',
		'status'
	];

	public function kyc_sub_level()
	{
		return $this->belongsTo(KycSubLevel::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class)
					->withPivot('id', 'kyc_credentials', 'status')
					->withTimestamps();
	}
}
