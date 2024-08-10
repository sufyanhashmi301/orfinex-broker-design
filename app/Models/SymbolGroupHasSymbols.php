<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymbolGroupHasSymbols extends Model
{
    use HasFactory;
    protected $table = 'symbol_group_has_symbols';

    protected $fillable = ['symbol_id', 'symbol_group_id', 'symbol_name'];
}
