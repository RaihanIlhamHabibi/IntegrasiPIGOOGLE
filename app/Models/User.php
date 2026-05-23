<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's Google token.
     */
    public function googleToken(): HasOne
    {
        return $this->hasOne(GoogleToken::class);
    }

    /**
     * Get all Google Drive files for the user.
     */
    public function googleDriveFiles(): HasMany
    {
        return $this->hasMany(GoogleDriveFile::class);
    }

    /**
     * Get all Google Calendar events for the user.
     */
    public function googleCalendarEvents(): HasMany
    {
        return $this->hasMany(GoogleCalendarEvent::class);
    }
}
