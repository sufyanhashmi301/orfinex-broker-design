<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lead_pipeline_id',
        'pipeline_stage_id',
        'lead_id',
        'close_date',
        'value',
        'note',
        'added_by',
    ];

    public function pipeline()
    {
        return $this->belongsTo(LeadPipeline::class, 'lead_pipeline_id');
    }

    public function pipelineStage()
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function notes()
    {
        return $this->hasMany(DealNote::class, 'deal_id');
    }
}
