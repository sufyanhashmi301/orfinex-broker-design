<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employment
 * 
 * @property int $id
 * @property string $title
 * @property string $desc
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Employment extends Model
{
	protected $table = 'employments';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'title',
		'desc',
		'status'
	];

	public function users()
	{
		return $this->hasMany(User::class);
	}
}
