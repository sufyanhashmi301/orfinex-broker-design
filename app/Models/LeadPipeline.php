<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadPipeline extends Model
{
    use HasFactory;

    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class, 'lead_pipeline_id', 'id')->orderBy('pipeline_stages.priority');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'lead_pipeline_id');
    }
    
}
