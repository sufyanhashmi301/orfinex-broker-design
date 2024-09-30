<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemTag
 *
 * @property int $id
 * @property string $name
 * @property string|null $desc
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SystemTag extends Model
{
    protected $table = 'system_tags'; // Table name

    protected $casts = [
        'status' => 'int' // Cast status as integer
    ];

    protected $fillable = [
        'name', // Fillable attributes
        'desc',
        'status'
    ];

    public function systemTags()
    {
        return $this->belongsToMany(User::class, 'system_tag_user'); // Update with appropriate pivot table if needed
    }
}
