<?php

namespace App\Services;

use App\Models\Designation;
use Illuminate\Support\Str;

class DesignationService
{
    public function create(array $data): Designation
    {
        $data['slug'] = Str::slug($data['name']);
        return Designation::create($data);
    }

    public function update(Designation $designation, array $data): Designation
    {
        $data['slug'] = Str::slug($data['name']);
        $designation->update($data);
        return $designation;
    }

    public function delete(Designation $designation): void
    {
        $designation->delete();
    }
}