<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WalletBalance
 *
 * @property string|null $email
 * @property string|null $balance
 *
 * @package App\Models
 */
class   WalletBalance extends Model
{
	protected $table = 'wallet_balances';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'email',
		'balance'
	];
}
