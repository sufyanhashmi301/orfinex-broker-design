<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Coderflex\LaravelTicket\Models\Ticket as TicketModel;

class Ticket extends TicketModel implements HasMedia
{
    use InteractsWithMedia;

    // Removed getCreatedAtAttribute to allow proper timezone conversion in controllers/views
    // Use toSiteTimezone() in DataTables and blade views for display

    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid)->first();
    }

    public function messages(): HasMany
    {
        $tableName = config('laravel_ticket.table_names.messages', 'messages');
        return $this->hasMany(Message::class, (string) $tableName['columns']['ticket_foreing_id'],);
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.admins.model'), 'assigned_to');
    }

}
