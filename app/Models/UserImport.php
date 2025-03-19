<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserImport
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $f_name
 * @property string|null $l_name
 * @property string|null $m_name
 * @property string|null $group
 * @property string|null $country
 * @property string|null $email
 * @property string|null $leverage
 * @property string|null $register_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserImport extends Model
{
	protected $table = 'user_imports';

	protected $fillable = [
		'login',
		'f_name',
		'l_name',
		'm_name',
		'group',
		'country',
		'email',
		'phone',
		'kyc',
		'leverage',
		'register_time',
		'staff_name',
		'staff_email',
		'ib_name',
		'ib_email',
	];
}
