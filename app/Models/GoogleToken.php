<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleToken extends Model
{
    protected $fillable = [
        'user_id',
        'google_id',
        'access_token',
        'refresh_token',
        'expires_at',
        'token_type',
        'scope',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'scope' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThanOrEqualTo($this->expires_at);
    }
}
