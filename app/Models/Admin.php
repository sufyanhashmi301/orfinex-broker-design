<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    protected $casts = [
        'ib_groups' => 'array',
        'account_types' => 'array',
    ];
    protected $fillable = [
        'employee_id',
        'department_id',
        'designation_id',
        'role',
        'avatar',
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'work_phone',
        'password',
        'device_token',
        'is_admin',
        'status',
        'employment_type',
        'employment_status',
        'source_of_hire',
        'location',
        'timezone',
        'date_of_joining',
        'date_of_birth',
        'gender',
        'marital_status',
        'google2fa_secret',
        'two_fa',
        'session_expiry',
        'ib_groups',
        'account_types',
        'key',
        'referral_code',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'staff_user', 'staff_id', 'user_id');
    }
    public function getFullNameAttribute(): string
    {
        $firstName = $this->attributes['first_name'] ?? '';
        $lastName = $this->attributes['last_name'] ?? '';

        return ucwords("{$firstName} {$lastName}");
//        return ucwords("{$this->attributes['first_name']} {$this->attributes['last_name']}");
    }

    // Removed getCreatedAtAttribute to allow proper timezone conversion in controllers/views
    // Use toSiteTimezone() in DataTables and blade views for display

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function notes()
    {
        return $this->hasMany(Note::class, 'admin_id');
    }

    /**
     * Get the branches assigned to this admin
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'admin_branches')
                    ->withTimestamps();
    }

    /**
     * Check if admin has access to a specific branch
     */
    public function hasAccessToBranch($branchId)
    {
        // Super-Admin has access to all branches
        if ($this->hasRole('Super-Admin')) {
            return true;
        }

        return $this->branches()->where('branch_id', $branchId)->exists();
    }

    /**
     * Assign branches to this admin
     */
    public function assignToBranches(array $branchIds)
    {
        return $this->branches()->sync($branchIds);
    }

    /**
     * Get accessible branch IDs for this admin
     */
    public function getAccessibleBranchIds()
    {
        // Super-Admin has access to all branches
        if ($this->hasRole('Super-Admin')) {
            return Branch::pluck('id')->toArray();
        }

        return $this->branches()->pluck('branch_id')->toArray();
    }
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // No action needed
    }

    public function getRememberTokenName()
    {
        return null;
    }
    public function getLinkAttribute()
    {
        return  url('/register?invite=' .$this->getOrCreateReferralCode());
    }
    public function getOrCreateReferralCode()
    {
        if (!$this->referral_code) {
            $this->generateCode();
            $this->save();
        }
        return $this->referral_code;
    }

    private function generateCode()
    {
        $this->referral_code = Str::random(setting('referral_code_limit', 'global'));
    }
    // app/Models/Admin.php

// Add these to your existing Admin model
public function teamMembers()
{
    return $this->belongsToMany(Admin::class, 'staff_team', 'manager_id', 'member_id')
        ->withTimestamps();
}

public function managingStaff()
{
    return $this->belongsToMany(Admin::class, 'staff_team', 'member_id', 'manager_id')
        ->withTimestamps();
}

// Helper methods
public function isTeamManagerOf(Admin $staff)
{
    return $this->teamMembers()->where('member_id', $staff->id)->exists();
}
public function getAllAttachedUsers()
{
    // Get directly attached users
    $directUsers = $this->users;
    
    // Get users from team members (recursively)
    $teamUsers = $this->teamMembers->flatMap(function($member) {
        return $member->getAllAttachedUsers();
    });
    
    return $directUsers->merge($teamUsers)->unique('id');
}
public function getTeamHierarchy($level = 0, $maxLevel = 3)
{
    if ($level >= $maxLevel) return collect();
    
    return $this->teamMembers()->with(['roles', 'teamMembers' => function($q) use ($level, $maxLevel) {
        $q->with(['roles', 'teamMembers' => function($q) use ($level, $maxLevel) {
            if ($level < $maxLevel - 2) {
                $q->with('teamMembers');
            }
        }]);
    }])->get();
}
}
