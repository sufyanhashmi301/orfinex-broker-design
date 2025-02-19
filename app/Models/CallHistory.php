<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = [
        'contact_id', 'user_id', 'call_sid', 'direction', 'from', 'to', 'type', 'duration', 'status', 'recordings',
    ];

    public function contact()
    {
        return $this->belongsTo(UserContact::class, 'deal_id', 'contact_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setToAttribute($value)
    {
        // Remove all whitespaces from the number before saving
        $this->attributes['to'] = preg_replace('/\s+/', '', $value);
    }

    public function getFromAttribute($value)
    {
        return $value == 'test_client' ? env('TWILIO_PHONE_NUMBER') : $value;
    }

}