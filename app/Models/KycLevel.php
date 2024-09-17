<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KycLevel
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Kyc[] $kycs
 *
 * @package App\Models
 */
class KycLevel extends Model
{
	protected $table = 'kyc_levels';

	protected $casts = [
		'status' => 'bool'
	];

	protected $fillable = [
		'name',
		'slug',
		'status'
	];

	public function kyc_sub_levels()
	{
		return $this->hasMany(KycSubLevel::class);
	}
    public function kycs()
    {
        return $this->hasMany(Kyc::class);
    }
}
