<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PrimexUser
 *
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $created
 * @property string|null $status
 * @property string $email
 * @property string|null $phone
 * @property string|null $country
 * @property string|null $city
 * @property string $verification_level
 * @property Carbon|null $last_login
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PrimexUser extends Model
{
	protected $table = 'primex_users';

	protected $casts = [
		'created' => 'datetime',
		'last_login' => 'datetime'
	];

	protected $fillable = [
		'old_id',
		'name',
		'created',
		'status',
		'email',
		'phone',
		'country',
		'city',
		'verification_level',
		'last_login'
	];
}
