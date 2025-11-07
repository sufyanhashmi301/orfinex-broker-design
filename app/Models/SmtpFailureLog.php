<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpFailureLog extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'context' => 'array',
        'shortcodes' => 'array',
        'resent_at' => 'datetime',
    ];
    
    /**
     * Scope to get recent failures (last hour)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subHour());
    }
    
    /**
     * Scope to get today's failures
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope to get only failed (not resent) logs
     */
    public function scopeFailed($query)
    {
        return $query->whereNull('resent_at');
    }

    /**
     * Scope to get only resent logs
     */
    public function scopeResent($query)
    {
        return $query->whereNotNull('resent_at');
    }
    
    /**
     * Get the email template relationship
     */
    public function emailTemplateRelation()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template', 'code');
    }
    
    /**
     * Get the user relationship by email
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'recipient', 'email');
    }
    
    /**
     * Get the formatted template name
     */
    public function getTemplateNameAttribute()
    {
        // Try to get from email templates
        $emailTemplate = $this->emailTemplateRelation;
        if ($emailTemplate) {
            return $emailTemplate->name;
        }
        
        // Format the code if no template found
        if ($this->email_template) {
            return ucwords(str_replace(['_', '-'], ' ', $this->email_template));
        }
        
        return 'Unknown';
    }
}

