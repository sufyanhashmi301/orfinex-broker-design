<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id', 'status'];

    public function parent()
    {
        return $this->belongsTo(Designation::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Designation::class, 'parent_id');
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
        return $this->belongsToMany(Admin::class, 'designation_has_staff', 'designation_id', 'staff_id');
    }
}
