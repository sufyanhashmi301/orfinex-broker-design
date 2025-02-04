<?php

namespace App\Services;

use App\Models\LeadStage;

class LeadStageService
{
    public function create(array $data): LeadStage
    {
        return LeadStage::create($data);
    }

    public function getAll()
    {
        return LeadStage::paginate(10);
    }

    public function getById(int $id): LeadStage
    {
        return LeadStage::findOrFail($id);
    }

    public function update(int $id, array $data): LeadStage
    {
        $stage = LeadStage::findOrFail($id);
        $stage->update($data);
        return $stage;
    }

    public function delete(int $id): bool
    {
        $stage = LeadStage::findOrFail($id);
        return $stage->delete();
    }
}
