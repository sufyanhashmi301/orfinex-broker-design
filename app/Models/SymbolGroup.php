<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymbolGroup extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function symbols()
    {
        return $this->belongsToMany(Symbol::class, 'symbol_group_has_symbols', 'symbol_group_id', 'symbol_id')
                    ->withPivot('symbol_name');
    }
}
