<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'label_color', 'pipeline'];

    public function deals()
    {
        return $this->hasMany(Deal::class, 'pipeline_stage_id');
    }
}
