<?php

namespace App\Services;

use App\Models\LeadSource;

class LeadSourceService
{
    public function create(array $data): LeadSource
    {
        return LeadSource::create($data);
    }

    public function getAll()
    {
        return LeadSource::paginate(10);
    }

    public function getById(int $id): LeadSource
    {
        return LeadSource::findOrFail($id);
    }

    public function update(int $id, array $data): LeadSource
    {
        $source = LeadSource::findOrFail($id);
        $source->update($data);
        return $source;
    }

    public function delete(int $id): bool
    {
        $source = LeadSource::findOrFail($id);
        return $source->delete();
    }
}
