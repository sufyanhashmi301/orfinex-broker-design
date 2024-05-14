<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FundCertificate
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property float $profit
 * @property string $path
 * @property Carbon $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class FundCertificate extends Model
{
	protected $table = 'fund_certificates';

	protected $casts = [
		'user_id' => 'int',
		'profit' => 'float',
		'date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'pricing_invest_id',
		'code',
		'profit',
		'path',
		'date'
	];
}
