<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
        'two_fa'
    ];


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

}
