<?php

namespace App\Services;

use App\Models\CustomerGroup;
use Illuminate\Support\Str;

class CustomerGroupService
{
    public function create(array $data): CustomerGroup
    {
   
        $data['slug'] = Str::slug($data['name']);
        
        return CustomerGroup::create($data);
    }

    public function update(CustomerGroup $customerGroup, array $data): CustomerGroup
    {
        $data['slug'] = Str::slug($data['name']);
        $customerGroup->update($data);
        return $customerGroup;
    }

    public function delete(CustomerGroup $customerGroup): void
    {
        $customerGroup->delete();
    }
}