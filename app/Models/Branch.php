<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $casts = [
        'status' => 'int'
    ];

    protected $fillable = [
        'name',
        'code', 
        'status'
    ];

    /**
     * Scope for active branches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get users assigned to this branch via user_metas
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            UserMeta::class,
            'meta_value', // Foreign key on user_metas table
            'id',         // Foreign key on users table
            'id',         // Local key on branches table
            'user_id'     // Local key on user_metas table
        )->where('user_metas.meta_key', 'branch_id');
    }

    /**
     * Get admins assigned to this branch via admin_branches pivot
     */
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_branches')
                    ->withTimestamps();
    }

    /**
     * Get transactions for this branch
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'branch_id');
    }

    /**
     * Get deposit methods for this branch
     */
    public function depositMethods()
    {
        return $this->hasMany(DepositMethod::class, 'branch_id');
    }

    /**
     * Get withdraw methods for this branch
     */
    public function withdrawMethods()
    {
        return $this->hasMany(WithdrawMethod::class, 'branch_id');
    }

    /**
     * Get forex schemas (account types) for this branch
     */
    public function forexSchemas()
    {
        return $this->hasMany(ForexSchema::class, 'branch_id');
    }

    /**
     * Get IB groups for this branch
     */
    public function ibGroups()
    {
        return $this->hasMany(IbGroup::class, 'branch_id');
    }
}