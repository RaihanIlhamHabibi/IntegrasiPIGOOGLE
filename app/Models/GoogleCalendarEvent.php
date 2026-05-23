<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleCalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'google_event_id',
        'event_title',
        'event_description',
        'event_start',
        'event_end',
        'location',
        'calendar_id',
        'hangout_link',
        'status',
        'all_day',
    ];

    protected $casts = [
        'event_start' => 'datetime',
        'event_end' => 'datetime',
        'all_day' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
