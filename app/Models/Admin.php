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

    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i');
    }

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
}
