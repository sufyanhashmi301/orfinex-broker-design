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

    protected $fillable = ['avatar', 'name', 'email', 'phone', 'password', 'device_token', 'is_admin', 'status','google2fa_secret','two_fa'];


    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_has_staff', 'staff_id', 'department_id');
    }

    public function designations()
    {
        return $this->belongsToMany(Designation::class, 'designation_has_staff', 'staff_id', 'designation_id');
    }
}
