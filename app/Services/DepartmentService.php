<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentService
{
    public function create(array $data): Department
    {
        $data['slug'] = Str::slug($data['name']);
        return Department::create($data);
    }

    public function update(Department $department, array $data): Department
    {
        $data['slug'] = Str::slug($data['name']);
        $department->update($data);
        return $department;
    }

    public function delete(Department $department): void
    {
        $department->delete();
    }
}