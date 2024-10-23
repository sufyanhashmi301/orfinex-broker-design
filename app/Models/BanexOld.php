<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BanexOld
 * 
 * @property int $id
 * @property string|null $login
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $group
 * @property string|null $country
 * @property string|null $email
 * @property string|null $balance
 * @property string|null $equity
 * @property string|null $profit
 * @property string|null $floating
 * @property string|null $currency
 * @property string|null $lead_score
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class BanexOld extends Model
{
	protected $table = 'banex_olds';

	protected $fillable = [
		'login',
		'first_name',
		'last_name',
		'group',
		'country',
		'email',
		'balance',
		'equity',
		'profit',
		'floating',
		'currency',
		'lead_score'
	];
}
