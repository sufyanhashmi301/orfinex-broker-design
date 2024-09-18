<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'subject', 'message_body', 'button_level', 'button_link', 'bottom_status', 'bottom_body', 'status', 'disclaimer', 'risk_warning'];
    protected $guarded = ['id'];
}
