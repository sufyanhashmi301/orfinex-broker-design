<?php

namespace App\Services;

use App\Models\TicketStatus;

class TicketStatusService
{
    public function create(array $data): TicketStatus
    {
        return TicketStatus::create($data);
    }

    public function getAll()
    {
        return TicketStatus::all();
    }

    public function getById(int $id): TicketStatus
    {
        return TicketStatus::findOrFail($id);
    }

    public function update(int $id, array $data): TicketStatus
    {
        $status = TicketStatus::findOrFail($id);
        $status->update($data);
        return $status;
    }

    public function delete(int $id): bool
    {
        $status = TicketStatus::findOrFail($id);
        return $status->delete();
    }
}
