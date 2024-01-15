<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdvertisementMaterial
 * 
 * @property int $id
 * @property string $img
 * @property string $language
 * @property string $type
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AdvertisementMaterial extends Model
{
	protected $table = 'advertisement_materials';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'img',
		'language',
		'type',
		'status'
	];
}
