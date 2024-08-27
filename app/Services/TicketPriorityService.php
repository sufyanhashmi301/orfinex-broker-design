<?php

namespace App\Services;

use App\Models\TicketPriority;

class TicketPriorityService
{
    public function create(array $data): TicketPriority
    {
        return TicketPriority::create($data);
    }

    public function getAll()
    {
        return TicketPriority::all();
    }

    public function getById(int $id): TicketPriority
    {
        return TicketPriority::findOrFail($id);
    }

    public function update(int $id, array $data): TicketPriority
    {
        $priority = TicketPriority::findOrFail($id);
        $priority->update($data);
        return $priority;
    }

    public function delete(int $id): bool
    {
        $priority = TicketPriority::findOrFail($id);
        return $priority->delete();
    }
}
