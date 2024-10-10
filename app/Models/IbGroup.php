<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbGroup extends Model
{
    use HasFactory;

    protected $table = 'ib_groups'; // Table name

    protected $casts = [
        'status' => 'int' // Cast status as integer
    ];

    protected $fillable = [
        'name', // Fillable attributes
        'desc',
        'status'
    ];

    /**
     * Define a many-to-many relationship with the User model.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'ib_group_user'); // Update with your pivot table if needed
    }
    public function multiLevels()
    {
        return $this->belongsToMany(MultiLevel::class, 'ib_group_multi_level', 'ib_group_id', 'multi_level_id'); // Update with your pivot table name if needed
    }
}
