<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function groups()
    {
        return $this->belongsToMany(SymbolGroup::class, 'symbol_group_has_symbols', 'symbol_id', 'symbol_group_id')
                    ->withPivot('symbol_name');
    }
}
