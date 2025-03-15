<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;
    protected $fillable = ['title', 'link', 'slug', 'status'];

}
