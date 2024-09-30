<?php

namespace App\Services;

use App\Models\Discount;

class DiscountService
{
    // Create a new discount
    public function create(array $data)
    {
        return Discount::create($data);
    }

    // Update an existing discount
    public function update(Discount $discount, array $data)
    {
        return $discount->update($data);
    }

    // Delete a discount
    public function delete(Discount $discount)
    {
        return $discount->delete();
    }
}
