<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RebateRule extends Model
{
    use HasFactory;

    protected $guarded =[];
    public function groups()
    {
        return $this->belongsToMany(SymbolGroup::class, 'rebate_rule_has_groups', 'rebate_rule_id', 'symbol_group_id');
                    
    }
}
