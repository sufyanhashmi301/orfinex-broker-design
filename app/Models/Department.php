<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug','department_email', 'parent_id', 'status','hide_from_client'];

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
    public function allDescendants()
    {
        return $this->children()->with('allDescendants');
    }

    public function descendants()
    {
        $descendants = collect();
        $children = $this->allDescendants()->get();

        foreach ($children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }
    public function staff()
    {
        return $this->belongsToMany(Admin::class, 'department_has_staff', 'department_id', 'staff_id');
    }
}
