<?php

namespace App\Models;

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
        return $this->belongsToMany(User::class, 'user_ib_rules')
            ->withPivot('rebate_rule_id', 'sub_ib_share') // Add pivot attributes
            ->withTimestamps();
    }

    /**
     * Multi-level relationship.
     */
    public function multiLevels()
    {
        return $this->belongsToMany(MultiLevel::class, 'ib_group_multi_level', 'ib_group_id', 'multi_level_id');
    }

    /**
     * Forex Schemas relationship.
     */
    public function forexSchemas()
    {
        return $this->hasMany(ForexSchema::class, 'ib_group_id', 'id');
    }

    /**
     * Rebate Rules relationship.
     */
    public function rebateRules()
    {
        return $this->belongsToMany(RebateRule::class, 'ib_group_rebate_rule', 'ib_group_id', 'rebate_rule_id');
    }

    /**
     * User IB Rules relationship.
     */
    public function userIbRules()
    {
        return $this->hasMany(UserIbRule::class, 'ib_group_id');
    }
}

