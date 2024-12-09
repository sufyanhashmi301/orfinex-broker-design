<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialLink
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $driver
 * @property string|null $client_id
 * @property string|null $client_secret
 * @property string|null $redirect
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SocialLink extends Model
{
	protected $table = 'social_links';

	protected $casts = [
		'status' => 'int'
	];


	protected $fillable = [
		'title',
		'slug',
		'driver',
		'client_id',
		'client_secret',
		'redirect',
		'status'
	];
    public static function activePlatforms()
    {
        return self::where('status', 1)->get();
    }

    public static function getProviderConfig($provider)
    {
        return self::where('slug', $provider)->where('status', 1)->first();
    }

}
